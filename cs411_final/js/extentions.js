$.fn.dataTableExt.oApi.fnFakeRowspan = function ( oSettings, iColumn, bCaseSensitive ) {
    /*
     * Type:        Plugin for DataTables (http://datatables.net) JQuery plugin.
     * Name:        dataTableExt.oApi.fnFakeRowspan
     * Requires:    DataTables 1.6.0+
     * Version:     1.0.1
     * Description: Creates rowspan cells in a column when there are two or more
     *              cells in a row with the same content. It only works for
     *              server-side processing as cells are removed and can't be
     *              sorted by DataTables.
     *             
     * Inputs:      object:oSettings - dataTables settings object
     *              integer:iColumn - the column to fake rowspans in
     *              boolean:bCaseSensitive - whether the comparison is case-sensitive or not (default: true)
     * Returns:     JQuery
     * Usage:       $('#example').dataTable().fnFakeRowspan(3);
     *              $('#example').dataTable().fnFakeRowspan(3, false);
     *
     * Author:      Fredrik Wendel
     * Created:     2010-02-10
     * Language:    Javascript
     * License:     GPL v2 or BSD 3 point style
     */
     
    /* Fail silently on missing/errorenous parameter data. */
    if (isNaN(iColumn)) {
        return false;
    }
     
    if (iColumn < 0 || iColumn > oSettings.aoColumns.length-1) {
        alert ('Invalid column number choosen, must be between 0 and ' + (oSettings.aoColumns.length-1));
        return false;
    }
     
    var oSettings = oSettings,
        iColumn = iColumn,
        bCaseSensitive = (typeof(bCaseSensitive) != 'boolean' ? true : bCaseSensitive);
 
    oSettings.aoDrawCallback.push({ "fn": fakeRowspan, "sName": "fnFakeRowspan" });
 
    function fakeRowspan () {
        var firstOccurance = null,
            value = null,
            rowspan = 0;
        jQuery.each(oSettings.aoData, function (i, oData) {
            var val = oData._aData[iColumn],
                cell = oData.nTr.childNodes[iColumn];
            /* Use lowercase comparison if not case-sensitive. */
            if (!bCaseSensitive) {
                val = val.toLowerCase();
            }
            /* Reset values on new cell data. */
            if (val != value) {
                value = val;
                firstOccurance = cell;
                rowspan = 0;
            }
             
            if (val == value) {
                rowspan++;
            }
             
            if (firstOccurance !== null && val == value && rowspan > 1) {
                oData.nTr.removeChild(cell);
                firstOccurance.rowSpan = rowspan;
				$(firstOccurance).addClass('fake_rowspan');
            }
        });
    }
     
    return this;
}

$.extend({ // jQuery extension to get url parameters
  getUrlVars: function(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
      hash = hashes[i].split('=');
      vars.push(hash[0]);
      vars[hash[0]] = hash[1];
    }
    return vars;
  },
  getUrlVar: function(name){
    return $.getUrlVars()[name];
  }
});