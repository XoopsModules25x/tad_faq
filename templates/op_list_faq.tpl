<script type="text/javascript">
$(document).ready(function(){
    <{if $smarty.session.tad_faq_adm|default:false}>
        $("#sort").sortable({ opacity: 0.6, cursor: "move", update: function() {
            var order = $(this).sortable("serialize");
            $.post("save_sort.php", order, function(theResponse){
                $("#save_msg").html(theResponse);
            });
        }
        });
    <{/if}>



    $(".faq_content").hide();


    $(".faq_title").click(function () {
        let fqsn=$(this).data("sn");
        let content_id="#tr_"+fqsn+"_ans";
        $(".faq_content").css("background-color: ","white");

        $(content_id).slideToggle(function(){
        $.post("ajax.php", { sn: fqsn}, function(data) {
            $("#counter_"+fqsn).html(data);
        });
        }).css("background-color","#F4F9EA");

    });
});
</script>

<{if $cates|is_array && $cates|@count > 0}>
    <div class="alert alert-info">
        <div class="input-group">
            <div class="input-group-prepend input-group-addon">
                <span class="input-group-text"><{$smarty.const._MD_TADFAQ_CHANGE_CATE}></span>
            </div>
            <select id="fcsn_select" class="form-control form-select" placeholder="<{$smarty.const._MD_TADFAQ_SELECT_CATE}>" onchange="location.href='index.php?fcsn='+this.value" title="<{$smarty.const._MD_TADFAQ_SELECT_CATE}>">
            <{foreach from=$cates key=of_fcsn item=of_fcsn_cate}>
                <{foreach from=$of_fcsn_cate key=fcsn item=cate}>
                    <option value="<{$fcsn|default:''}>" <{if $smarty.get.fcsn == $fcsn}>selected<{/if}>><{$cate.title}></option>
                <{/foreach}>
            <{/foreach}>
            </select>
        </div>

    </div>
<{/if}>

<h2><{$cate_title|default:''}></h2>

<{if $smarty.session.tad_faq_adm|default:false || $faq_edit_power|default:false}>
    <a href="index.php?op=tad_faq_content_form&fcsn=<{$fcsn|default:''}>" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> <{$smarty.const._TAD_ADD}></a>
<{/if}>

<{assign var="n" value=1}>
<div id="sort">
<{foreach from=$faq item=data}>
    <{if $data.enable|default:false || $smarty.session.tad_faq_adm|default:false || $edit_power|default:false}>
        <div class="faq_title well card card-body bg-light m-1" id="tr_<{$data.fqsn}>" data-sn="<{$data.fqsn}>">
        <div class="row">
                <div class="col-sm-10">
                    <a name="#<{$data.fqsn}>" id="<{$data.fqsn}>" class="<{if $data.enable!="1"}>disabled<{/if}>" style="text-align:left;padding:4px 10px;">
                        <{if $data.enable=="1"}>
                        <{$n|default:''}>.
                        <{assign var="n" value=$n+1}>
                        <{else}>
                        ?.
                        <{/if}>
                        <{$data.title}>
                    </a>
                </div>

                <div class="col-sm-2 text-right text-end">
                    <{if $smarty.session.tad_faq_adm|default:false || $edit_power|default:false}>
                        <a href="index.php?op=update_status&fcsn=<{$fcsn|default:''}>&fqsn=<{$data.fqsn}>&enable=<{$data.update_enable}>" class="btn btn-sm btn-xs btn-info"><i class="fa fa-power-off " aria-hidden="true"></i> <{$data.enable_txt}></a>
                        <{if $data.enable!="1"}>
                        <a href="javascript:delete_tad_faq_content_func(<{$data.fqsn}>);" class="btn btn-sm btn-xs btn-danger"><i class="fa fa-trash" aria-hidden="true"></i> <{$smarty.const._TAD_DEL}></a>
                        <{/if}>
                        <a href="index.php?op=tad_faq_content_form&fqsn=<{$data.fqsn}>" class="btn btn-sm btn-xs btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> <{$smarty.const._TAD_EDIT}></a>
                    <{/if}>
                    <span class="badge badge-info bg-info" id="counter_<{$data.fqsn}>"><{$data.counter}></span>
                </div>
            </div>
        </div>


        <div id="tr_<{$data.fqsn}>_ans" class="well card card-body m-1 faq_content" style="line-height: 1.8;">
            <{$data.content}>
        </div>
    <{/if}>
<{/foreach}>
</div>
<div id="save_msg"></div>