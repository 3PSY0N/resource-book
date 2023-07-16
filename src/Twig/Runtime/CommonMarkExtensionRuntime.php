<?php

namespace App\Twig\Runtime;

use App\Service\CommonMark\MarkerExtension\MarkerExtension;
use App\Service\CommonMark\YouTube;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\DefaultAttributes\DefaultAttributesExtension;
use League\CommonMark\Extension\DisallowedRawHtml\DisallowedRawHtmlExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\Footnote\FootnoteExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\Mention\MentionExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\Table\Table;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Output\RenderedContentInterface;
use Twig\Extension\RuntimeExtensionInterface;
use Ueberdosis\CommonMark\EmbedExtension;
use Ueberdosis\CommonMark\Services\Vimeo;


class CommonMarkExtensionRuntime implements RuntimeExtensionInterface
{
    private Environment $environment;
    private MarkdownConverter $converter;

    public function __construct()
    {
        $this->setConfig();
    }

    /**
     * @throws CommonMarkException
     */
    public function markdownify($text): ?RenderedContentInterface
    {
        if ($text) {
            return $this->converter->convert($text);
        }

        return null;
    }

    public function setConfig(): void
    {
        $config = [
            'table' => [
                'wrap' => [
                    'enabled' => true,
                    'tag' => 'div',
                    'attributes' => [
                        'class' => 'table-overflow'
                    ],
                ],
                'alignment_attributes' => [
                    'left'   => ['align' => 'left'],
                    'center' => ['align' => 'center'],
                    'right'  => ['align' => 'right'],
                ],
            ],
            'disallowed_raw_html' => [
                'disallowed_tags' => ['title', 'textarea', 'input', 'style', 'xmp', 'iframe', 'noembed', 'noframes', 'script', 'plaintext'],
            ],
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
            'max_nesting_level' => 10,
            'external_link' => [
                'internal_hosts' => 'www.example.com', // TODO: Don't forget to set this!
                'open_in_new_window' => true,
                'html_class' => 'external-link',
                'nofollow' => 'external',
                'noopener' => 'external',
                'noreferrer' => 'external',
            ],
            'default_attributes' => [
                Heading::class => [
                    'class' => static function (Heading $node) {
//                        if ($node->getLevel() === 1) {
                            return 'group font-rajdhani font-medium';
//                        } else {
//                            return null;
//                        }
                    },
                ],
                Table::class => [
                    'class' => 'table-auto',
                ],
                Paragraph::class => [
                    'class' => [],
                ],
            ],
            'embeds' => [
                new YouTube(),
                new Vimeo(),
            ],
            'footnote' => [
                'backref_class'      => 'fn-backref',
                'backref_symbol'     => '↩',
                'container_add_hr'   => true,
                'container_class'    => 'footnotes',
                'ref_class'          => 'fn-ref',
                'ref_id_prefix'      => 'ref:',
                'footnote_class'     => 'footnote',
                'footnote_id_prefix' => 'go-ref:',
            ],
            'heading_permalink' => [
                'html_class' => 'permalink',
                'id_prefix' => '',
                'apply_id_to_heading' => false,
                'heading_class' => '',
                'fragment_prefix' => '',
                'insert' => 'before',
                'min_heading_level' => 1,
                'max_heading_level' => 6,
                'title' => 'Permalink',
//                'symbol' => HeadingPermalinkRenderer::DEFAULT_SYMBOL,
                'symbol' => '#',
                'aria_hidden' => true,
            ],
            'mentions' => [
                // Twitter handler mention configuration.
                // Sample Input:  `@colinodell`
                // Sample Output: `<a href="https://www.twitter.com/colinodell">@colinodell</a>`
                // Note: when registering more than one mention parser with the same prefix, the first mention parser to
                // successfully match and return a properly constructed Mention object (where the URL has been set) will be
                // the mention parser that is used. In this example, the GitHub handle would actually match first because
                // there isn't any real validation to check whether https://www.github.com/colinodell exists. However, in
                // CMS applications, you could check whether it's a local user first, then check Twitter and then GitHub, etc.
                'twitter_handle' => [
                    'prefix'    => '@',
                    'pattern'   => '[A-Za-z0-9_]{1,15}(?!\w)',
                    'generator' => 'https://twitter.com/%s',
                ],
            ],
        ];

        $this->environment = new Environment($config);
//        $this->environment->addExtension(new GithubFlavoredMarkdownExtension());
        $this->environment->addExtension(new CommonMarkCoreExtension());
        $this->environment->addExtension(new AttributesExtension());
        $this->environment->addExtension(new DefaultAttributesExtension());
        $this->environment->addExtension(new ExternalLinkExtension());
        $this->environment->addExtension(new AutolinkExtension());
        $this->environment->addExtension(new DisallowedRawHtmlExtension());
        $this->environment->addExtension(new StrikethroughExtension());
        $this->environment->addExtension(new TableExtension());
        $this->environment->addExtension(new TaskListExtension());
        $this->environment->addExtension(new EmbedExtension());
        $this->environment->addExtension(new FootnoteExtension());
        $this->environment->addExtension(new HeadingPermalinkExtension());
        $this->environment->addExtension(new MarkerExtension());
        $this->environment->addExtension(new MentionExtension());
        $this->converter = new MarkdownConverter($this->environment);
    }

}
