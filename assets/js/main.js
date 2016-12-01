var app = {
    generateData: function (generateDataUrl) {
       $('#generateDataBtn').on('click', function () {
           $.ajax({
               method: "POST",
               url: generateDataUrl,
               beforeSend: function() {
                   $('#generateDataBtn').text('Loading...');
                   $('#generateDataBtn').attr('disabled', true);

               },
               success: function () {
                   $('#generateDataBtn').text('SUCCESS!');
                   $('#generateDataBtn').attr('disabled', true);
               },
               error: function (err) {
               }
           });
       })
    },
    init: function () {
        $('#addButton').on('click', function () {
            $('#addModal').modal('show');
        });
    },

    table: {
        init: function (selector, settings, removeUrl) {
            var table = selector.DataTable(settings);
            selector.find('tbody').on('click', '.deleteBtn', function () {
                var data = table.row($(this).parents('tr')).data();
                app.table.removeRecord(removeUrl, data.id);
            });

            $('#arrivalDateTo, #arrivalDateFrom, #departureDateFrom, #departureDateTo').on('change', function() {
                table.ajax.reload();
            });
        },

        removeRecord: function (removeUrl, id) {
            $.ajax({
                "method": "POST",
                "url": removeUrl,
                "data": {id: id},
                success: function (data) {
                    if (data.result === 'success') {
                        location.reload();
                    }
                },
                error: function (err) {
                    // console.log(err);
                }
            })
        }
    },

    schedule: {
        init: function (getDisableDatesUrl, getTripTimeUrl) {
            $('.bootstrapDatepicker').datepicker({
                'container': '#filter',
                'autoclose': true,
                'format': 'yyyy-mm-dd'
            });
            $('#schedule_form_arrivalDate').val("Not enough data to calculate!");
            $('#schedule_form_departureDate').datepicker({
                'container': '#addModal',
                'autoclose': true,
                'format': 'yyyy-mm-dd'
            }).on('changeDate', function (e) {
                if(e.date != undefined) {
                    app.schedule.getTripTime($('#schedule_form_region option:selected').val(),
                        moment(e.date).format('YYYY-MM-DD'), getTripTimeUrl);
                }
            });

            $('#schedule_form_courier').on('change', function (e) {
                app.schedule.getDisabledDates(e.target.value, getDisableDatesUrl);
            }).trigger('change');
        },

        getDisabledDates: function (courierId, getDisableDatesUrl) {
            $.ajax({
                method: "GET",
                url: getDisableDatesUrl,
                data: {courierId: courierId},
                success: function (data) {
                    if(data != 'error') {
                        if(data.includes($('#schedule_form_departureDate').val())) {
                            $('#schedule_form_departureDate').datepicker('clearDates');
                            $('#schedule_form_arrivalDate').val("Not enough data to calculate!");
                        }
                        $('#schedule_form_departureDate').datepicker('setDatesDisabled', data);
                    }
                },
                error: function (err) {
                    // console.log(err);
                }
            })
        },

        getTripTime: function (regionId, departureTime, getTripTimeUrl) {
            $.ajax({
                method: "GET",
                url: getTripTimeUrl,
                data: {regionId: regionId},
                success: function(data) {
                    $('#schedule_form_arrivalDate').val(moment(departureTime).add(data, 'days').format('YYYY-MM-DD'));

                },
                error: function(err) {
                    // console.log(err);
                }
            });
        }

    }
};