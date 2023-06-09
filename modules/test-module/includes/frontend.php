<div class="product-research">
    <div class="container">
        <div class="row search-row row-30 justify-content-center align-items-center">
            <div class="col-12 input-container">
                <h1>Product Research</h1>
                <h3>Welcome to our beta version. Please keep in mind that we are currently developing the website, and as a result, many of the functionalities are still under development. We would appreciate it if you could provide us with your feedback.</h3>
            </div>
            <div class="col-md-8 col-sm-12 mt-3 input-product">
                <input type="text" id="input-product" name="product" placeholder="Enter Product Name">
            </div>
            <div class="col-md-4 col-sm-12 mt-3 input-button">
                <input type="submit" id="input-submit" name="submit" value="GO" onclick="gtag('event', 'click', {'event_category': 'Button', 'event_label': 'GO', 'product': document.querySelector('input[name=\'product\']').value});">
            </div>
        </div>
        <div class="row content-row">
            <div class="col-lg-4 col-md-12">
                <div class="content-box">
                    <img src="<?php echo plugin_dir_url( __DIR__ ); ?>/img/icon-3.svg" alt="">
                    <h1>Quickly Understand if a Winner or not.</h1>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="content-box">
                    <img src="<?php echo plugin_dir_url( __DIR__ ); ?>/img/icon-4.svg" alt="">
                    <h1>Unlimited Searches & Intelligent Answers.</h1>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="content-box">
                    <img src="<?php echo plugin_dir_url( __DIR__ ); ?>/img/icon-2.svg" alt="">
                    <h1>Increase Your Chances of Success.</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="output-wrapper">
    <!-- Page 2: Output  -->
    <div class="output-container" style="display: none;">
        <div class="output-intro">

            <h3 id="product-name"></h3>
            <p id="product-introduction"></p>

        </div>
        <div class="output-table">

            <div id="output-table" style="display: none;"></div>
            <div class="accordion" id="faq">
            </div>
            <div id="average__score"></div>
            <div class="overview ">
                <div class="row">
                    <!--<div class="col-md-6 col-sm-12 mt-3">
                        <div class="overview__item">
                            <div class="overview__item--title">
                                <img src="<?php echo plugin_dir_url( __DIR__ ); ?>/img/icon-1.svg" alt="">
                                <span>Strength</span>
                            </div>
                            <div class="overview__item--list">
                                <ul>
                                    <li>Protein bars often have
                                        supplements, powders,
                                    </li>
                                    <li>And other related products that can be upsold
                                    </li>
                                    <li>Cross-sold to customers</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 mt-3">
                        <div class="overview__item">
                            <div class="overview__item--title">
                                <img src="<?php echo plugin_dir_url( __DIR__ ); ?>/img/icon-1.svg" alt="">
                                <span>Strength</span>
                            </div>
                            <div class="overview__item--list">
                                <ul>
                                    <li>Protein bars often have
                                        supplements, powders,
                                    </li>
                                    <li>And other related products that can be upsold
                                    </li>
                                    <li>Cross-sold to customers</li>
                                </ul>
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>

            <div class="feedback">
                <div class="row">
                    <div class="col-12 text-center">
                        <h1>Give us Feedback!</h1>
                        <form id="feedback" method="POST">
                            <input name="feedback" type="text" placeholder="What are your thoughts?"><br>
                            <button type="submit">Send</button>
                        </form>
                        <p id="feedback-status"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- Loading screen -->
<div class="loading-container" style="display: none; background: url('<?php echo plugin_dir_url( __DIR__ ); ?>/img/loading-bg-image.png')">

    <div class="center-content">
        <img src="<?php echo plugin_dir_url( __DIR__ ); ?>/img/loading-45.gif">
        <h1>Calculationg Scores</h1>
        <input type="submit" id="cancel-search-btn" name="cancel-search-btn" value="CANCEL SEARCH">
    </div>

</div>


</div>


<script>
    jQuery(document).ready(function() {



        jQuery('#input-submit').click(function() {

            jQuery('.loading-container').fadeIn();
            jQuery('.input-container').fadeOut();


            var functions_calls = ['get_output_intro', 'get_output_table', 'get_output_advice'];
            //var functions_calls = ['get_output_intro'];
            var product_name = jQuery('#input-product').val();
            let deferreds = {};

            jQuery.each(functions_calls, function(index, function_call) {

                jQuery.ajax({
                    url: '<?php echo plugin_dir_url( __DIR__ ); ?>/ai-request.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        function: function_call,
                        product: product_name
                    },
                    success: function(response) {
                        deferreds[function_call] = "";
                        deferreds[function_call] = response;
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        alert('Error');
                        jQuery('.input-container').fadeIn();
                        jQuery('.loading-container').fadeOut();
                    }
                });
            });

            jQuery(document).ajaxStop(function() {
                // Remove previous content
                jQuery("#product-name").empty();
                jQuery("#product-introduction").empty();
                jQuery("#output-table").empty();
                jQuery("#product-advice").empty();
                jQuery("#faq").empty();
                jQuery("#average__score").empty();

                jQuery('#product-name').text(product_name);
                jQuery('#product-introduction').html(deferreds['get_output_intro']);
                jQuery('#output-table').html(deferreds['get_output_table']);
                jQuery('#product-advice').html(deferreds['get_output_advice']);

                //Hide benefits
                jQuery('.product-research .row.content-row').hide('.row.content-row');

                jQuery('.output-container').fadeIn();
                jQuery('.loading-container').fadeOut();

                // Create an empty array to store the table data
                var criteria = [];

                // Loop through each row in the table starting from the second row (index 1)
                jQuery('table tbody tr').each(function() {
                    var rowData = {};
                    var cells = jQuery(this).find('td');

                    // Get the text content of the cells and store them in the rowData object with corresponding keys
                    rowData.title = jQuery(cells[0]).text();
                    rowData.score = jQuery(cells[1]).text();
                    rowData.description = jQuery(cells[2]).text();

                    // Push the row data object to the table data array
                    criteria.push(rowData);
                });

                // Now, tableData is an array of objects with keys "title", "score", and "description"
                console.log(criteria);



                jQuery.each(criteria.slice(0, -1), function(index, item) {
                    //var colorClass = (item.score >= 80) ? "color-green" : "color-red";
                    var colorClass = (item.score < 70) ? "background: red;" : ((item.score <= 85) ? "background: black;" : "background: green;");
                    var accordionHtml = `
        <div class="card card-load criteria__item">
            <div class="card-header align-items-center" id="faqhead${index + 1}">
                <a href="#" class="btn btn-header-link" data-toggle="collapse" data-target="#faq${index + 1}" aria-expanded="true" aria-controls="faq${index + 1}">
                    <div class="row criteria__item--row">
                        <div class="col-6 justify-content-center">
                            <h3>${item.title}</h3>
                        </div>
                        <div class="col-3 text-right score">
                            <span style="${colorClass}">${item.score}</span>
                        </div>
                        <!--<div class="col-3 text-center">
                            <span>${item.score}</span>
                        </div>-->
                    </div>
                </a>
            </div>
            <div id="faq${index + 1}" class="collapse" aria-labelledby="faqhead${index + 1}" data-parent="#faq">
                <div class="card-body">
                    <div class="item-description">${item.description}</div>
                </div>
            </div>
        </div>
    `;
                    // Append the accordionHtml to the div with id "faq"
                    jQuery("#faq").append(accordionHtml);
                });

                var lastItem = criteria[criteria.length - 1];
                //var lastItemColorClass = (lastItem.score >= 80) ? "color-green" : "color-red";
                var lastItemColorClass = (lastItem.score < 70) ? "background: red;" : ((lastItem.score <= 85) ? "background: black;" : "background: green;");


                var avarage_score = `
        <div class="average__score--title">
            <h1>${lastItem.title}</h1>
            <h2 style="${lastItemColorClass}">${lastItem.score}</h2>
        </div>
        <div class="average__score--desc">
            <p>${lastItem.description}</p>
        </div>
        <!--<div class="average__score--btns">
            <a href="#" class="save">Save Result <i class="far fa-bookmark"></i></a>
            <a href="#" class="share">Share Result <i class="far fa-paper-plane"></i></a>
        </div>-->
    `;
                console.log(avarage_score);
                jQuery("#average__score").append(avarage_score);

                //bindCardLoadClickEvent();
            });

        });



        function bindCardLoadClickEvent() {
            jQuery('.card').click(function() {
                if (jQuery(this).hasClass('card-load')) {
                    
                    jQuery(this).removeClass('card-load');
                    var product_name = jQuery('#input-product').val();
                    var function_call = 'get_description_' + jQuery(this).find('h3').text().toLowerCase().replace(/ /g, '_') + '_' + jQuery(this).find('.score').text();

                    jQuery.ajax({
                        url: '<?php echo plugin_dir_url( __DIR__ ); ?>/ai-request.php',
                        type: 'POST',
                        dataType: 'html',
                        data: {
                            function: function_call,
                            product: product_name
                        },
                        success: function(response) {
                            jQuery(this).find('.item-description').text(response);
                        },
                        error: function(xhr, status, error) {
                            alert('Error');
                        }
                    });

                }
            });
        }


        jQuery('#cancel-search-btn').click(function() {
            // Hide the loading container and show the input container
            jQuery('.loading-container').fadeOut();
            jQuery('.input-container').fadeIn();
            // Abort any ongoing AJAX requests
            jQuery.ajaxAbort();
        });


        jQuery('#feedback').submit(function(event) {
            event.preventDefault(); // Prevent form from submitting normally
            
            // Get form data
            var formData = jQuery(this).serialize();

            var splitString = formData.split("=");
            var decodedValue = decodeURIComponent(splitString[1]);
            
            
            // Send form data using Ajax
            jQuery.ajax({
                type: 'POST',
                url: 'https://productresearch.ai/wp-content/plugins/openai-modules-for-beaver-builder/send-feedback.php', // Replace with the URL to your PHP file
                data: { feedback: decodedValue },
                success: function(response) {
                    if (response == 'success') {
                        jQuery('#feedback-status').html('<p>Your feedback was sent successfully. Thank you for getting in touch!</p>');
                    } else {
                        jQuery('#feedback-status').html('<p>Sorry, an error occurred while sending your feedback. Please try again later.</p>');
                    }
                }
            });
        });



    });
</script>
