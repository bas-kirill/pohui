<?php
echo <<<_END
    <style>
        #promo-category-panel {
            height: 50%;
            background-color: cornflowerblue;
        }

        #promo-category-table {
            background-color: darkgray;
        }
    </style>
    
    <div id="promo-category-panel">
        <span id="promo-category-title-text">10% discount on the promo code:</span>
        <div id="promo-category-table" class="text-center">
            <div id="promo-category-row-one" class="row">
                <div class="col-md-4">0, 0</div>
                <div class="col-md-4">0, 1</div>
                <div class="col-md-4">0, 2</div>
            </div>
            <div id="promo-category-row-two" class="row">
                <div class="col-md-4">1, 0</div>
                <div class="col-md-4">1, 1</div>
                <div class="col-md-4">1, 2</div>
            </div>
        </div>
    </div>
_END;
?>

