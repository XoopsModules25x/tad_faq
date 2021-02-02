<{if $block.content}>
    <ol class="vertical_menu">
    <{foreach from=$block.content item=faq}>
        <li>
            <img src="<{$xoops_url}>/modules/tad_faq/images/comment_edit.png" alt="<{$faq.title}>" style="margin: 4px;">
            <a href="<{$xoops_url}>/modules/tad_faq/index.php?fcsn=<{$faq.fcsn}>"><{$faq.title}> <{$faq.num}></a>
        </li>
    <{/foreach}>
    </ol>
<{/if}>