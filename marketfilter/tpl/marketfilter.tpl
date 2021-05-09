<!-- BEGIN: MAIN -->
<form action="{SEARCH_ACTION_URL}" method="POST" class="uk-form uk-margin-large-bottom" id="marketfilter">
    {SEARCH_SAVE}
    <fieldset data-uk-margin>
        <legend>{PHP.L.marketfilter_sort}</legend>
        {SEARCH_SORTER}
    </fieldset>
    <fieldset data-uk-margin>
        <legend>{PHP.L.marketfilter_price}</legend>
        <div class="uk-grid uk-margin-bottom">
            <div class="uk-width-1-2">{SEARCH_PRICE1}</div>
            <div class="uk-width-1-2">{SEARCH_PRICE2}</div>
        </div>
        <div id="marketfilter-range" class="uk-margin-bottom"></div>
    </fieldset>
    <fieldset data-uk-margin>
        <legend>{PHP.L.marketfilter_cats}</legend>
        {SEARCH_CAT}
    </fieldset>
</form>
<script>
    $(function() {
        $( "#marketfilter-range" ).slider({
            range: true,
            min: 0,
            max: {SEARCH_PRICE_MAX},
            values: [ {SEARCH_PRICE_VALUES1}, {SEARCH_PRICE_VALUES2} ],
            slide: function( event, ui ) {
                $( '[name="price1"]' ).val( ui.values[ 0 ] );
                $( '[name="price2"]' ).val( ui.values[ 1 ] );
            },
            change: function (){  
                var url = $("#marketfilter").attr("action");  
                applyFilter(url);
            }        
        });
        $("#marketfilter").on("change", function (){
            var url = $(this).attr("action");     
            applyFilter(url);
        });
        function applyFilter(url){
            $("#marketfilter-content").css("opacity", 0.5);
            $.ajax({
                url: url,
                method: "post",
                data: $("#marketfilter").serialize(),
            }).done(function(h) {
               var con =  $(h).find("#marketfilter-content");
               $("#marketfilter-content").html(con);
               $("#marketfilter-content").css("opacity", 1);
            });  
        }
    });
</script>
<!-- END: MAIN -->
