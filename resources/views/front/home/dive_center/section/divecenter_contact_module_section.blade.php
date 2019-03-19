<div id="contact-form">
    <form name="contact" class="ui form">
        <div class="ui two stackable fields grid " >
            <h3 class="ui sixteen wide center aligned column">Contact Us</h3>
            <div class="field sixteen wide column center aligned">
                <input type="text" name="email" placeholder="Email" id="email">
                <p id="error-email" class="red margin-top-10"></p>
            </div>

            <div class="field sixteen wide column center aligned">
                <textarea name="message" placeholder="Message" rows="2" id="message"></textarea>
            </div>

            <div class="field sixteen wide column center aligned">
                <button type="button"
                        class="ui primary toggle button"
                        id="send-message"
                        data-loading-text="<i class='fa fa-spinner fa-spin '></i> Sending Request">
                Send Now</button>
            </div>
        </div>
    </form>
</div>