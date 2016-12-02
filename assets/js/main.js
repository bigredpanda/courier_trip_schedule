var app = {
    generateData: function (generateDataUrl) {
        this.generateBtn = $('#generateDataBtn');
        this.generateBtn.on('click', function () {
            $.ajax({
                method: "POST",
                url: generateDataUrl,
                beforeSend: function () {
                    app.generateBtn.text('Loading...');
                    app.generateBtn.attr('disabled', true);

                },
                success: function () {
                    app.generateBtn.text('SUCCESS!');
                    app.generateBtn.attr('disabled', true);
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

            $('#arrivalDateTo, #arrivalDateFrom, #departureDateFrom, #departureDateTo').on('change', function () {
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
            this.departureDate = $('#schedule_form_departureDate');
            this.arrivalDate = $('#schedule_form_arrivalDate');

            $('.bootstrapDatepicker').datepicker({
                'container': '#filter',
                'autoclose': true,
                'format': 'yyyy-mm-dd'
            });
            this.arrivalDate.val("Not enough data to calculate!");
            this.departureDate.datepicker({
                'container': '#addModal',
                'autoclose': true,
                'format': 'yyyy-mm-dd'
            }).on('changeDate clearDate', function (e) {
                if (e.date != undefined) {
                    app.schedule.getTripTime($('#schedule_form_region option:selected').val(),
                        moment(e.date).format('YYYY-MM-DD'), getTripTimeUrl);
                } else {
                    app.schedule.arrivalDate.val("Not enough data to calculate!");
                }
            }).on('keyup', function () {
                $(this).val('');
            });

            $('#schedule_form_courier').on('change', function (e) {
                app.schedule.getDisabledDates(e.target.value, getDisableDatesUrl);
            }).trigger('change');

            $('#schedule_form_region').on('change', function (e) {
                var date = app.schedule.departureDate.val();
                if (date != '') {
                    app.schedule.getTripTime(e.target.value,
                        moment(date).format('YYYY-MM-DD'), getTripTimeUrl);
                }
            });
        },

        getDisabledDates: function (courierId, getDisableDatesUrl) {
            $.ajax({
                method: "GET",
                url: getDisableDatesUrl,
                data: {courierId: courierId},
                success: function (data) {
                    if (data != 'error') {
                        if (data.includes(app.schedule.departureDate.val())) {
                            app.schedule.departureDate.datepicker('clearDates');
                            app.schedule.arrivalDate.val("Not enough data to calculate!");
                        }
                        app.schedule.departureDate.datepicker('setDatesDisabled', data);
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
                success: function (data) {
                    app.schedule.arrivalDate.val(moment(departureTime).add(data, 'days').format('YYYY-MM-DD'));

                },
                error: function (err) {
                    // console.log(err);
                }
            });
        }

    }
};