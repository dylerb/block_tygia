<!-- BEGIN: main  -->
<style type="text/css">
#exrateContainer{font:400 11px/18px tahoma,verdana,sans-serif;}
#exrateContainer table{background:#fff;border:1px solid #dadada;border-collapse:separate;border-spacing:1px;caption-side:top;empty-cells:show;width:100%;}
#exrateContainer table caption{font:bold 12px/20px tahoma;color:#666}
#exrateContainer table tbody{background:#f7f7f7;font:400 10px/18px tahoma,verdana,sans-serif;white-space:nowrap;}
#exrateContainer table tbody.second{background:#eee;}
#exrateContainer table td{padding:2px 4px;}
#exrateContainer table thead{background:#ccc;font:bold 10px/18px tahoma,verdana,sans-serif;white-space:nowrap;}
</style>
<div id="exrateContainer">
    <table>
        <caption>Vietcombank {UPDATE}</caption>
        <thead>
            <tr>
                <td>{LANG.CurrencyCode}</td>
                <td style="text-align:right">{LANG.buy}</td>
                <td style="text-align:right">{LANG.transfer}</td>
                <td style="text-align:right">{LANG.sell}</td>
            </tr>
        </thead>
        <!-- BEGIN: loop -->
        <tbody{LOOP.class}>
            <tr>
                <td title="{LOOP.CurrencyName}">{LOOP.CurrencyCode}</td>
                <td style="text-align:right">
                    {LOOP.Buy}
                </td>
                <td style="text-align:right">
                    {LOOP.Transfer}
                </td>
                <td style="text-align:right">
                    {LOOP.Sell}
                </td>
            </tr>
        </tbody>
        <!-- END: loop -->
    </table>
</div>
<!-- END: main -->
