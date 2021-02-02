<link href="<{$xoops_url}>/modules/tadtools/css/font-awesome/css/font-awesome.css" rel="stylesheet">
<div class="container-fluid">

  <{if $show_error=='1'}>
  <{$error}>
    <div class="jumbotron">
      <h1><{$smarty.const._MA_TADFAQ_NO_SFAQ}></h1>
    </div>
  <{elseif $op=='listfaq'}>
    <div class="pull-right">
     <a href="copysfaq.php?op=import_faq&categoryid=<{$categoryid}>" class="btn btn-lg btn-info">Import All</a>
    </div>
    <div class="clearfix"></div>

    <table class="table table-striped table-bordered table-hover">
    <tr>
      <th>fqsn</th>
      <th>fcsn</th>
      <th>title</th>
      <th>sort</th>
      <th>uid</th>
      <th>post_date</th>
      <th>content</th>
      <th>enable</th>
      <th>counter</th>

    </tr>

    <tbody>
    <{foreach item=faq from=$all_content}>
      <tr>
        <td><{$faq.faqid}></td>
        <td><{$faq.categoryid}></td>
        <td><{$faq.question}></td>
        <td><{$faq.weight}></td>
        <td><{$faq.uid}></td>
        <td><{$faq.datesub}></td>
        <td><{$faq.answer}></td>
        <td>1</td>
        <td><{$faq.counter}></td>
      </tr>
    <{/foreach}>
    </tbody>
    </table>
    <div class="clearfix"></div>

  <{else}>
    <a href="copysfaq.php?op=copyfaq" class="btn btn-lg btn-info pull-right">copy all</a>
    <table class="table table-striped table-bordered table-hover">
    <tr>
      <th>#</th>
      <th>Function</th>
      <th>categoryid</th>
      <th>parentid</th>
      <th>name</th>
      <th>description</th>
      <th>total</th>
      <th>weight</th>
      <th>created</th>
      <th>tools</th>
      <th>FAQs</th>
    </tr>

    <tbody>

    <{foreach item=faq from=$all_content}>

    <tr>
      <td><{$faq.i}></td>
      <td><a href="copysfaq.php?op=delsfaq&categoryid=<{$faq.categoryid}>" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a></td>
      <td><{$faq.categoryid}></td>
      <td><{$faq.parentid}></td>
      <td><{$faq.name}></td>
      <td><{$faq.description}></td>
      <td><{$faq.total}></td>
      <td><{$faq.weight}></td>
      <td><{$faq.created}></td>
      <td>
        <{if $faq.exist==0}>
        done
        <{elseif $faq.exist > 0}>
          <a href="copysfaq.php?op=listfaq&categoryid=<{$faq.categoryid}>"<{if $faq.exist > 0 and $faq.faq_number==0}>class="btn btn-xs btn-danger"<{/if}>><{$faq.exist}> FAQs</a>
        <{else}>
          <a href="copysfaq.php?op=copyfaq&categoryid=<{$faq.categoryid}>">copy</a>
        <{/if}>
      </td>
      <td><{$faq.faq_number}></td>
      </tr>
    <{/foreach}>



    </tbody>

    <tr>
      <td colspan=6 class='bar'>
      <{$add_button}>
      <{$bar}>
      </td>
    </tr>
    </table>
  <{/if}>
</div>