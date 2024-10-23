$( document ).ready(function() {

    // apply eventListeners
    applyClickListeners();
    applyFocusOutListeners();
    applyChangeListeners();


    fillConfigSelectbox();

    ////////////////////////////// START CLICK LISTENERS + FUNCTIONS //////////////////////////////

    function applyClickListeners() {

        $('#newConfigButton').on('click', (event) => {
            
            openConfigForm();

        });

        $('#manualButton').on('click', (event) => {
            
            openManualForm();

        });

       $('#config_submit').on('click', (event) => {

            var hostId = 'config_host';
            var userId = 'config_user';
            var passwordId = 'config_password';
            var portId = 'config_port';

            var host = $(`#${hostId}`).val();
            var user = $(`#${userId}`).val();
            var password = $(`#${passwordId}`).val();
            var port = $(`#${portId}`).val();

            var url = `../Controller/router.php`;

            $.post({url: url}, {action: 'addConfig', [hostId]: host, [userId]: user, [passwordId]: password, [portId]: port})

            .done((response) => {
                clearConfigForm();
                closeConfigForm();
                alert('Connection added successfully :)');

                fillConfigSelectbox();
            })

            .fail((response) => {
                alert('Failed to add connection :(');
            });
        });

        $('#generateGetterSetterManual').on('click', (event) => {
            event.preventDefault();
        
            var attributesId = 'attributesFormControlTextareaManual';
            var attributes = $('#' + attributesId).val(); 

            var visibilityGetId = 'isPrivateGetter';
            var visibilitySetId = 'isPrivateSetter';
            var visibilityGet = $('#' + visibilityGetId).is(':checked');
            var visibilitySet = $('#' + visibilitySetId).is(':checked');
            
            var url = `../Controller/router.php`;
        
            // Send an AJAX POST request using jQuery
            $.post(url, {
                action: 'addManual',
                [attributesId]: attributes,
                [visibilityGetId]: visibilityGet,
                [visibilitySetId]: visibilitySet
            })

            .done(function(response) {
                response = JSON.parse(response);

                $('#contentManual > p').html(`<pre>${response.data}</pre>`);
                $('#contentManual > span').html('');
                $('#contentManual').attr('hidden', false);

            })
            .fail(function(xhr, status, error) {
                console.error('Error:', status, error); // Handle any error
            });
        });
        
        $('#backButton').on('click', (event) => {
            closeManualForm();
        });

       $('#generatePHPModel').on('click', (event) => {
            
            var configId = $('#configSelect').val();
            var databaseId = $('#databaseSelect').val();
            var tableId = $('#tableSelect').val();
            var url = `../Controller/router.php?action=generatePHPModel&configId=${configId}&databaseId=${databaseId}&tableId=${tableId}`;

            $.post({url: url})

            .done((response) => {
                response = JSON.parse(response);

                $('#content > p').html(`<pre>${response.data}</pre>`);
                $('#content > span').html('');
                $('#content').attr('hidden', false);
            })

            .fail((response) => {
                response = JSON.parse(response.responseText);

                $('#content > p').html('');
                $('#content > span').html(response.message);
            });

        });

       $('#generatePHPGateway').on('click', (event) => {
            
            var configId = $('#configSelect').val();
            var databaseId = $('#databaseSelect').val();
            var tableId = $('#tableSelect').val();
            var url = `../Controller/router.php?action=generatePHPGateway&configId=${configId}&databaseId=${databaseId}&tableId=${tableId}`;

            $.post({url: url})

            .done((response) => {
                response = JSON.parse(response);

                $('#content > p').html(`<pre>${response.data}</pre>`);
                $('#content > span').html('');
                $('#content').attr('hidden', false);
            })

            .fail((response) => {
                response = JSON.parse(response.responseText);

                $('#content > p').html('');
                $('#content > span').html(response.message);
            });

        });

        $('#generateExtJSModel').on('click', (event) => {
            
            var configId = $('#configSelect').val();
            var databaseId = $('#databaseSelect').val();
            var tableId = $('#tableSelect').val();
            var url = `../Controller/router.php?action=generateExtJSModel&configId=${configId}&databaseId=${databaseId}&tableId=${tableId}`;

            $.post({url: url})

            .done((response) => {
                response = JSON.parse(response);

                $('#content > p').html(`<pre>${response.data}</pre>`);
                $('#content > span').html('');
                $('#content').attr('hidden', false);
            })

            .fail((response) => {
                response = JSON.parse(response.responseText);

                $('#content > p').html('');
                $('#content > span').html(response.message);
            });

        });

        $('#generateExtJSGrid').on('click', (event) => {
            
            var configId = $('#configSelect').val();
            var databaseId = $('#databaseSelect').val();
            var tableId = $('#tableSelect').val();
            var url = `../Controller/router.php?action=generateExtJSGrid&configId=${configId}&databaseId=${databaseId}&tableId=${tableId}`;

            $.post({url: url})

            .done((response) => {
                response = JSON.parse(response);

                $('#content > p').html(`<pre>${response.data}</pre>`);
                $('#content > span').html('');
                $('#content').attr('hidden', false);
            })

            .fail((response) => {
                response = JSON.parse(response.responseText);

                $('#content > p').html('');
                $('#content > span').html(response.message);
            });

        });

        $('#generateExtJSAdd').on('click', (event) => {
            
            var configId = $('#configSelect').val();
            var databaseId = $('#databaseSelect').val();
            var tableId = $('#tableSelect').val();
            var url = `../Controller/router.php?action=generateExtJSAdd&configId=${configId}&databaseId=${databaseId}&tableId=${tableId}`;

            $.post({url: url})

            .done((response) => {
                response = JSON.parse(response);

                $('#content > p').html(`<pre>${response.data}</pre>`);
                $('#content > span').html('');
                $('#content').attr('hidden', false);
            })

            .fail((response) => {
                response = JSON.parse(response.responseText);

                $('#content > p').html('');
                $('#content > span').html(response.message);
            });

        });

        $('#generateExtJSEdit').on('click', (event) => {
            
            var configId = $('#configSelect').val();
            var databaseId = $('#databaseSelect').val();
            var tableId = $('#tableSelect').val();
            var url = `../Controller/router.php?action=generateExtJSEdit&configId=${configId}&databaseId=${databaseId}&tableId=${tableId}`;

            $.post({url: url})

            .done((response) => {
                response = JSON.parse(response);

                $('#content > p').html(`<pre>${response.data}</pre>`);
                $('#content > span').html('');
                $('#content').attr('hidden', false);
            })

            .fail((response) => {
                response = JSON.parse(response.responseText);

                $('#content > p').html('');
                $('#content > span').html(response.message);
            });

        });

        $('#generateExtJSInfo').on('click', (event) => {
            
            var configId = $('#configSelect').val();
            var databaseId = $('#databaseSelect').val();
            var tableId = $('#tableSelect').val();
            var url = `../Controller/router.php?action=generateExtJSInfo&configId=${configId}&databaseId=${databaseId}&tableId=${tableId}`;

            $.post({url: url})

            .done((response) => {
                response = JSON.parse(response);

                $('#content > p').html(`<pre>${response.data}</pre>`);
                $('#content > span').html('');
                $('#content').attr('hidden', false);
            })

            .fail((response) => {
                response = JSON.parse(response.responseText);

                $('#content > p').html('');
                $('#content > span').html(response.message);
            });

        });
    }

    $('#copyButton').on('click', (event) => {

            selectText('content');
            document.execCommand('copy');
            clearSelection();

        });

    $('#copyButtonManual').on('click', (event) => {
            
        selectText('contentManual');
        document.execCommand('copy');
        clearSelection();

    });

    function openConfigForm() {

        // hide "new config" button
        $('#newConfigButton').addClass('disabled');

        var card = $('#newConfigCard');
        card.removeClass('hidden');
        card.removeClass('no-opacity');
        card.removeClass('fade-out');
        card.addClass('fade-in');
            
        $('#config_host').focus();

    }

    function openManualForm() {

        // hide "new config" button
        $('#manualButton').addClass('disabled');

        var panelManual = $('#contentPanelManual');
        panelManual.removeClass('hidden');

        var mainPanel = $('#panel');
        mainPanel.removeClass('disabled');

        var panel = $('#contentPanel');
        panel.addClass('hidden');

    }

    function closeConfigForm() {

        document.body.focus();

        var card = $('#newConfigCard');
        card.removeClass('fade-in');
        card.addClass('fade-out');
        card.addClass('no-opacity');
        card.addClass('hidden');
        
        var button = $('#newConfigButton');
        button.removeClass('disabled');
        button.addClass('transition1');
    }

    function closeManualForm() {

        var contentPanelManual = $('#contentPanelManual');
        contentPanelManual.addClass('hidden');

        var contentPanel = $('#contentPanel');
        contentPanel.removeClass('hidden');

        var button = $('#manualButton');
        button.removeClass('disabled');

    }

    function clearConfigForm() {
        let inputs = $('#newConfigForm input');
        $.each( inputs, function( key, value ) {
            inputs[key].value = '';
        });
    }

    function selectText(containerid) {
        if (document.selection) { // IE
                var range = document.body.createTextRange();
                range.moveToElementText(document.getElementById(containerid));
                range.select();
            } else if (window.getSelection) {
                var range = document.createRange();
                range.selectNode(document.getElementById(containerid));
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(range);
            }
    }

    function clearSelection() {
        if (window.getSelection) {
            window.getSelection().removeAllRanges();
        } else if (document.selection) {
            document.selection.empty();
        }
    }

    ////////////////////////////// END CLICK LISTENERS + FUNCTIONS //////////////////////////////


    ////////////////////////////// START FOCUSOUT LISTENERS + FUNCTIONS //////////////////////////////

    function applyFocusOutListeners() {
        let inputs = $('#newConfigForm input');
        let submitButton = $('#config_submit'); 

        // apply for all inputs in form
        $.each( inputs, function( key, value ) {
            $(`#${value.id}`).on('focusout', (event) => {
                onConfigFormInputFocusout();
            });
        });

        // also apply focusout to add button so tabbing through inputs closes the dialog also
        submitButton.on('focusout', (event) => {    
            onConfigFormInputFocusout();
        });

    }

    function onConfigFormInputFocusout() {
        // check if a form field was filled or focused - if not close the dialog again
        // have to wait for 1ms so document.activeElement is set correctly
        setTimeout(() => {
            let close = true;

            //check if a form field is focused
            if(document.activeElement.id.includes('config_')) {
                close = false;
            }

            //check if at least 1 form field is filled
            let inputs = $('#newConfigForm input');
            $.each( inputs, function( key, value ) {
                value = $(value);
                if(value.val().length > 0) {
                    close = false;
                }
            });

            if(close) {
                closeConfigForm();
            }

        }, "1");

    }

    ////////////////////////////// END FOCUSOUT LISTENERS + FUNCTIONS //////////////////////////////
    

    ////////////////////////////// START CHANGE LISTENERS + FUNCTIONS //////////////////////////////

    function applyChangeListeners() {

        let configSelect = $('#configSelectDiv > select');
        let connErrorSpan = configSelect.siblings('span');

        let dbSelectDiv = $('#databaseSelectDiv');
        let dbSelect = dbSelectDiv.find('select');
        let dbLoader = $('#databaseSelectLoader');
        let dbErrorSpan = dbSelect.siblings('.error');

        let tableSelectDiv = $('#tableSelectDiv');
        let tableSelect = tableSelectDiv.find('select');
        let tableLoader = $('#tableSelectLoader');
        let tableErrorSpan = tableSelect.siblings('span');

        configSelect.on('change', (event) => {

            var configId = configSelect.val();

            //show label and loader of next selectbox 
            dbSelectDiv.removeClass('invisible');
            dbSelectDiv.addClass('fade-in-quick');
            dbLoader.attr('hidden', false);

            // clear error message
            dbErrorSpan.html('');
            

            // get possible databases for chosen config
            $.get({url: `../Controller/router.php?action=getDatabases&configId=${configId}`})

                .done((response) => {
                    response = JSON.parse(response);

                    dbSelect.attr('hidden', false);
                    dbLoader.attr('hidden', true);

                    dbSelect.html(new Option('Choose...', 0));

                    response.data.forEach((d) => {
                        dbSelect.append(new Option(d.name, d.id));
                    });

                })

                .fail((response) => {
                    response = JSON.parse(response.responseText);

                    dbLoader.attr('hidden', true);
                    dbErrorSpan.html(response.message);
                });
        });


        dbSelect.on('change', (event) => {


            //show loader and selectbox
            tableLoader.attr('hidden', false);
            tableSelectDiv.removeClass('invisible');
            tableSelectDiv.addClass('fade-in-quick');
            

            // get possible tables for chosen config
            var configId = configSelect.val();
            var databaseId = dbSelect.val();
            // TODO: get possible databases for chosen config by ajax request 
            $.get({url: `../Controller/router.php?action=getTables&databaseId=${databaseId}&configId=${configId}`})

            .done((response) => {
                response = JSON.parse(response);

                tableSelect.attr('hidden', false);
                dbLoader.attr('hidden', true);

                panel = $('#panel');
                panel.removeClass('disabled');

                tableSelect.html(new Option('Choose...', 0));

                response.data.forEach((d) => {
                    tableSelect.append(new Option(d.name, d.id));
                });

            })

            .fail((response) => {
                response = JSON.parse(response.responseText);

                tableLoader.attr('hidden', true);
                tableErrorSpan.html(response.message);
            });

        });

        tableSelect.on('change', (event) => {
            removeOverlay();
        });

    }

    ////////////////////////////// END CHANGE LISTENERS + FUNCTIONS //////////////////////////////


    ////////////////////////////// START CORE FUNCTIONS //////////////////////////////

    function fillConfigSelectbox() {
        
        let selectbox = $('#configSelectDiv > select');
        let errorSpan = selectbox.siblings('span');

        $.get({url: `../Controller/router.php?action=getConfigs`})

        .done((response) => {
            response = JSON.parse(response);

            selectbox.html(new Option('Choose...', 0));
            response.data.forEach((c) => {
                selectbox.append(new Option(c.host, c.id));
            });
        })

        .fail((response) => {
            response = JSON.parse(response.responseText);

            selectbox.html(new Option('Error'));
            selectbox.attr('disabled', true);
            errorSpan.html(response.message);
        });
        
    }

    function removeOverlay() {
        $('#contentPanel').removeClass('disabled');
        $('.fancyButton').removeClass('disabled');
    }

    ////////////////////////////// END CORE FUNCTIONS //////////////////////////////

});