<link href="<{$xoops_url}>/modules/tadtools/css/font-awesome/css/font-awesome.css" rel="stylesheet">
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <h2><{$smarty.const._MA_TADFAQ_CATE_INPUT_FORM}></h2>
      <{if $all_content}>
      <{$jquery}>
      <script type='text/javascript'>
        function delete_tad_faq_cate_func(fcsn){
          var sure = window.confirm("<{$smarty.const._TAD_DEL_CONFIRM}>");
          if (!sure)  return;
          location.href="main.php?op=delete_tad_faq_cate&fcsn=" + fcsn;
        }

        $(document).ready(function(){
            $('#sort').sortable({ opacity: 0.6, cursor: 'move', update: function() {
                var order = $(this).sortable('serialize');
                $.post('save_sort.php', order, function(theResponse){
                    $('#save_msg').html(theResponse);
                });
            }
            });
        });

      </script>
      <div id='save_msg'></div>
      <table class="table table-striped table-bordered table-hover">
        <tr>
          <th><{$smarty.const._MA_TADFAQ_SORT}></th>
          <th><{$smarty.const._MA_TADFAQ_TITLE}></th>
          <th><{$smarty.const._MA_TADFAQ_DESCRIPTION}></th>
          <th><{$smarty.const._MA_TADFAQ_ACCESS_POWER}></th>
          <th><{$smarty.const._MA_TADFAQ_EDIT_POWER}></th>
          <th><{$smarty.const._TAD_FUNCTION}></th>
        </tr>
        <tbody id='sort' >
        <{foreach from=$all_content item=faq}>
          <tr id='tr_<{$faq.fcsn}>'>
            <td><img src='<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png' style='cursor: s-resize;margin:0px 4px;' alt='' title=''><{$faq.sort}></td>
            <td><{$faq.title}></td>
            <td><{$faq.description}></td>
            <td><{$faq.faq_read}></td>
            <td><{$faq.faq_edit}></td>
            <td>
              <a href="javascript:delete_tad_faq_cate_func(<{$faq.fcsn}>);" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
              <a href="main.php?op=tad_faq_cate_form&fcsn=<{$faq.fcsn}>" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
            </td>
          </tr>
        <{/foreach}>
        </tbody>
        <tr>
      </table>
      <{/if}>


      <form action="main.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
        <div class="row">
          <div class="col-sm-9">
            <div class="form-group">
              <label class="col-sm-2 control-label">
                <{$smarty.const._MA_TADFAQ_TITLE}>
              </label>
              <div class="col-sm-10">
                <input type="text" name="title" value="<{$title}>" class="form-control">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">
                <{$smarty.const._MA_TADFAQ_CONTENT}>
              </label>
              <div class="col-sm-10">
                <{$editor}>
              </div>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="row">
              <label><{$smarty.const._MA_TADFAQ_SET_ACCESS_POWER}></label>
              <{$faq_read_group}>
            </div>
            <div class="row">
              <label><{$smarty.const._MA_TADFAQ_SET_EDIT_POWER}></label>
              <{$faq_edit_group}>
            </div>
            <div class="row text-center">
              <input type="hidden" name="fcsn" value="<{$fcsn}>">
              <input type="hidden" name="op" value="<{$op}>">
              <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
            </div>
          </div>
        </div>
      </form>

    </div>
  </div>

</div>