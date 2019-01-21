</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="libs/metisMenu/dist/metisMenu.min.js"></script>
<script src="libs/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="libs/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
<script src="js/sb-admin-2.js"></script>
<script type="text/javascript" class="init">
    $("#list").dataTable({
        "oLanguage": {
            "sLengthMenu": "表示行数 _MENU_ 件",
            "oPaginate": {
                "sNext": "次のページ",
                "sPrevious": "前のページ"
            },
            "sInfo": "合計: _TOTAL_件  _START_～_END_件を表示",
            "sSearch": "検索：",
            "sZeroRecords" : "データが見つかりません",
            "sInfoEmpty" : "データが見つかりません",
            "sInfoFiltered" : "(全_MAX_件より、フィルタリング)",
            "sProcessing" : "しばらくお待ちください…"
        }
    });
    $("#csvex").click(function(){
        if (!confirm('CSVをエクスポートします\nよろしいですか？')) {
            return false;
        }
    });
</script>