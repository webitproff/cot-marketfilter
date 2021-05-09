<!-- BEGIN: MAIN -->
<ul<!-- IF {LEVEL} == 0 --> class="uk-list"<!-- ENDIF -->>
    <!-- IF {LEVEL} == 0 -->
    <li<!-- IF {ROW_SELECTED} --> class="active"<!-- ENDIF -->>
        <label>
            <input type="checkbox" name="rcats[]" value="0"> Все
        </label>
    </li>
    <!-- ENDIF -->
    <!-- BEGIN: CATS -->
    <li<!-- IF {ROW_SELECTED} --> class="active"<!-- ENDIF -->>
        <label>
            <input type="checkbox" name="rcats[{ROW_KEY}]" value="1" {ROW_SELECTED}> {ROW_TITLE}  
        </label>
        <!-- IF {ROW_SUBCAT} -->
        {ROW_SUBCAT}
        <!-- ENDIF -->
    </li>
    <!-- END: CATS -->
</ul>
<!-- END: MAIN -->