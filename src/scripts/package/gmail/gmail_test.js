jQuery(document).ready(function($) {
    class Gmail_test {
        constructor() {
            this.gmail_test_form = $('#oe_gmail_test')
            this.events();
        }
        events() {
            this.gmail_test_form.on('submit', this.handle_click);
        }
        handle_click(e) {
            e.preventDefault();
            const form_data = $(this).serialize();
            $.ajax({
                url: file_url.send_email_url,
                data: form_data,
                type: 'post',
                success: res => {
                    console.log(res)
                    switch (JSON.parse(res).res) {
                        case 'empty_field':
                            $('.oe-notification').html(`
                                    <div class="notice notice-error is-dismissible">
                                        <p> Email is required to be sent! </p> 
                                    </div>
                            `).hide().slideDown();
                            break;

                        case 'success':
                            $('.oe-notification').html(`
                                    <div div class = "notice notice-success is-dismissible" >
                                        <p> Email sent successfully. Check inbox</p> 
                                    </div>
                            `).hide().slideDown();
                            break;

                        default:
                            $('.oe-notification').html(`
                                    <div class="notice notice-error is-dismissible">
                                        <p> An error occured while sending email </p> 
                                    </div>
                            `).hide().slideDown();
                            break;
                    }
                    setTimeout(() => {
                        $('.oe-notification').slideUp();
                    }, 3000);
                }
            })
        }
    }
    new Gmail_test();
})