CSV_OUTPUT_CTL = {

    exportCsv : function(data,fileName) {

        var a = document.createElement("a");
        document.body.appendChild(a);
        a.style = "display: none";
        url = CSV_OUTPUT_CTL.createCsvDlLink(data);
        a.href = url;
        a.download = fileName;
        a.click();
    },

    /**
     * データCSVのリンクを生成します.
     * data : mapの配列
     */
    createCsvDlLink : function(data) {

        if (data == null) {
            return;
        }

        var csv = [];

        var size = data.length;
        for (var i = 0; i < size; i++ ) {
            var record = data[i];
            var row = [];

            for(key in record){
                row.push('"'+record[key]+'"');
            }

            csv.push(row);
        }

        // BOM付でダウンロード
        var csvbuf = csv.map(function(e){return e.join(',')}).join('\r\n');

        var bom = new Uint8Array([0xEF, 0xBB, 0xBF]);
        var blob = new Blob([bom, csvbuf], { type: 'text/csv' });
        var url = (window.URL || window.webkitURL).createObjectURL(blob);
        var csvbuf = csv.map(function(e){return e.join(',')}).join('\r\n');

        var bom = new Uint8Array([0xEF, 0xBB, 0xBF]);
        var blob = new Blob([bom, csvbuf], { type: 'text/csv' });
        var url = (window.URL || window.webkitURL).createObjectURL(blob);

        return url;
    }
}