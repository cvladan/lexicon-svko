<?php

namespace SVKO\Lexicon;

use DOMDocument, DOMXPath, DOMNode;

class CrossLinker
{
    private
        $DOM = false,
        $XPath = false,
        $skip_elements = [],
        $link_complete_words_only = true,
        $replace_phrases_once = true,
        $link_target = '_self',
        $escape_tags = ['script', 'style', 'code', 'pre'], # These tags will not be loaded inside the PHP DOMDocument object
        $cache_expression = '{STRINGCACHE:%s}',
        $content_wrapper = 'content-wrapper',
        $data_cache = [];

    public function loadContent(string $content)
    {
        if (!class_exists('DOMDocument') || !class_exists('DOMXPath'))
            return false;

        #$content = MB_Convert_Encoding($content, 'HTML-ENTITIES', 'UTF-8');
        $content = $this->escapeTags($this->escape_tags, $content);
        $content = '<?xml encoding="UTF-8">' . "<{$this->content_wrapper}>{$content}</{$this->content_wrapper}>";

        $this->DOM = new DOMDocument();
        libxml_use_internal_errors(true);
        if (!@$this->DOM->loadHTML($content)) return false; # Here we could get a Warning if the $content is no valid HTML
        $this->DOM->encoding = 'UTF-8';
        $this->XPath = new DOMXPath($this->DOM);

        return true;
    }

    private function escapeTags(array $tags, string $content): string
    {
        if (!is_array($tags)) return $content;
        foreach ($tags as $tag) {
            $regex = sprintf('%%(<%1$s\b[^>]*>)(.*)(</%1$s>)%%imsuU', $tag);
            $content = preg_replace_callback($regex, [$this, 'cacheMatch'], $content);
        }
        return $content;
    }

    private function cacheMatch(array $match): string
    {
        $string = $match[2];
        $key = 'MD5:' . md5($string);
        $this->data_cache[$key] = $string;
        return $match[1] . sprintf($this->cache_expression, $key) . $match[3];
    }

    private function uncacheStrings(string $content): string
    {
        $this->data_cache = array_reverse($this->data_cache);
        foreach ($this->data_cache as $key => $string) {
            $content = str_replace(sprintf($this->cache_expression, $key), $string, $content);
        }
        $this->data_cache = [];
        return $content;
    }

    public function setSkipElements(array $elements): void
    {
        $elements = is_array($elements) ? $elements : [];
        $this->skip_elements = $elements;
    }

    public function linkCompleteWordsOnly(bool $state = true): void
    {
        $this->link_complete_words_only = (bool) $state;
    }

    public function replacePhrasesOnce(bool $state = true): void
    {
        $this->replace_phrases_once = (bool) $state;
    }

    public function setLinkTarget(string $target): void
    {
        $this->link_target = $target;
    }

    public function linkPhrase(string $phrase, callable $callback, array $callback_args = [])
    {
        # Check if there is a valid XPath object available
        if (!$this->XPath) return false;

        # Prepare search term
        $phrase = trim($phrase);
        $phrase = wptexturize($phrase); # This is necessary because the content runs through this filter, too
        $phrase = html_entity_decode($phrase, ENT_QUOTES, 'UTF-8');
        $phrase = htmlspecialchars($phrase);
        $phrase = preg_quote($phrase, '/');

        # Prepare search
        $word_boundary = ($this->link_complete_words_only) ? '\b' : '';
        $pattern_modifiers = 'imsuU';
        $search_regex = sprintf('/(%1$s)%%s(%1$s)/%2$s', $word_boundary, $pattern_modifiers);
        $link_regex = '<a href="%1$s" target="%2$s" title="%3$s" class="' . PostType::getPostTypeName() . '">$0</a>';
        $search = sprintf($search_regex, $phrase);
        $item = null;
        $link = null;

        # Build XPath to find all text elements and skip non-text elements like images and videos
        $xpath_query = '//text()[not(ancestor::*[contains(@class,"no-cross-linking")])]';
        foreach ($this->skip_elements as $skip_element) {
            $xpath_query .= sprintf('[not(ancestor::%s)]', $skip_element);
        }
        $document_nodes = $this->XPath->query($xpath_query);

        # Go through nodes and replace
        foreach ($document_nodes as $original_node) {
            $original_text = $original_node->wholeText;
            #$original_text = html_entity_decode($original_text, ENT_QUOTES, 'UTF-8');
            $original_text = htmlspecialchars($original_text);

            if (preg_match($search, $original_text)) {
                if (empty($item) && is_callable($callback)) {
                    $item = call_user_func_array($callback , $callback_args);
                    $xml_title = html_entity_decode($item->title, ENT_QUOTES, 'UTF-8');
                    $link = sprintf($link_regex, $item->url, $this->link_target, esc_attr($xml_title));
                    $link = apply_filters('lexicon_cross_link_element', $link, $item->url, $this->link_target, $item->title, $this);
                }

                # This could break if your terms contains very special characters which break the search regex
                $new_text = @preg_replace($search, $link, $original_text, ($this->replace_phrases_once ? 1 : -1));

                # Replace the original node with a new node which contains the new cross link
                $this->setNodeContent($original_node, $new_text);

                # We only replace the first match of this term with a link
                if ($this->replace_phrases_once) break;
            }
        }
    }

    private function setNodeContent(DOMNode $node, string $new_html): void
    {
        $new_node = $this->DOM->createDocumentFragment();

        # If the $new_html is not valid XML this will break
        if (@$new_node->appendXML($new_html)) {
            $node->parentNode->replaceChild($new_node, $node);
        }
    }

    public function getParserDocument(): string
    {
        if (!$this->DOM) return false;
        $resultHTML = $this->DOM->saveHTML();

        $head_start = '<head>';
        $head_start_pos = mb_strpos($resultHTML, $head_start, 0, 'UTF-8');
        $head_end = '</head>';
        $head_end_pos = mb_strpos($resultHTML, $head_end, $head_start_pos + strlen($head_start), 'UTF-8');
        $head = ($head_start_pos && $head_end_pos) ? mb_substr($resultHTML, $head_start_pos + strlen($head_start), $head_end_pos - $head_start_pos - strlen($head_start)) : '';

        $body_start = "<body><{$this->content_wrapper}>";
        $body_start_pos = mb_strpos($resultHTML, $body_start, 0, 'UTF-8');
        $body_end = "</{$this->content_wrapper}></body>";
        $body_end_pos = mb_strpos($resultHTML, $body_end, $body_start_pos + strlen($body_start), 'UTF-8');
        $body = ($body_start_pos && $body_end_pos) ? mb_substr($resultHTML, $body_start_pos + strlen($body_start), $body_end_pos - $body_start_pos - strlen($body_start)) : '';

        $html = $this->uncacheStrings($head . $body);
        return $html;
    }
}
