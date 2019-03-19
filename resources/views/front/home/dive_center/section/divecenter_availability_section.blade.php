<div class= "ui raised segments side-segment" id ="check-availability">
    <form name="product_availablity">
        <div class="ui two stackable fields grid " >
            <h3 class="ui sixteen wide center aligned column">Check Availability</h3>
            <div class="field sixteen wide column center aligned">
                <label>Check in:</label>
                <div class="ui calendar" id="rangestart">
                    <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" id="check_in" name="check_in"
                               placeholder="Start">
                        <br>
                    </div>
                </div>
                <p id="check-in-error"></p>
            </div>
            <div class="field sixteen wide column center aligned">
                <label>Check out:</label>
                <div class="ui calendar" id="rangeend">
                    <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" id="check_out" name="check_out" placeholder="End">
                    </div>
                </div>
                <p id="check-out-error"></p>
            </div>
            <div class="field sixteen wide column center aligned">
                <button type="button" class="ui primary button" id="check-product-availability">Check Now</button>
            </div>
        </div>
    </form>
</div>