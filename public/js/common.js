$(function () {
            var dateFormat = "yy/mm/dd",
                    from = $("#from")
                    .datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        changeYear: true,
                        numberOfMonths: 1,
                        dateFormat: 'yy/mm/dd'
                    })
                    .on("change", function () {
                        to.datepicker("option", "minDate", getDate(this));
                    }),
                    to = $("#to").datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        changeYear: true,
                        numberOfMonths: 1,
                        dateFormat: 'yy/mm/dd'
                    })
                    .on("change", function () {
                        from.datepicker("option", "maxDate", getDate(this));
                    });

            function getDate(element) {
                var date;
                try {
                    date = $.datepicker.parseDate(dateFormat, element.value);
                } catch (error) {
                    date = null;
                }

                return date;
            }
        });

        

        /* Custom filtering function which will search data in column four between two values */
        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var min = parseInt($('#min').val(), 10);
                var max = parseInt($('#max').val(), 10);
                var price = parseFloat(data[1]) || 0; // use data for the price column

                if ((isNaN(min) && isNaN(max)) ||
                        (isNaN(min) && price <= max) ||
                        (min <= price && isNaN(max)) ||
                        (min <= price && price <= max))
                {
                    return true;
                }
                return false;
            }
                    
        );

        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var valid = true;
                var min = moment($("#from").val());
                if (!min.isValid()) { min = null; }

                var max = moment($("#to").val());
                if (!max.isValid()) { max = null; }
                console.log(min + max);
                if (min === null && max === null) {
                    // no filter applied or no date columns
                    valid = true;
                    
                } else {
                    
                    $.each(settings.aoColumns, function (i, col) {

                        if (col.type == "date") {
                            var cDate = moment(data[i]);

                            if (cDate.isValid()) {
                                if (max !== null && max.isBefore(cDate)) {
                                    valid = false;
                                }
                                if (min !== null && cDate.isBefore(min)) {
                                    valid = false;
                                }
                            }
                            else {
                                valid = false;
                            }
                        }
                    });
                }
                return valid;
            }
                    
        );
        
        
        $(document).ready(function () {
            
            $('#from, #to').change(function () {
                $('#example1').DataTable().draw();
            });
            
            var table = $('#example1').DataTable(
                {"lengthChange": false, columns:[{name:"Name"},
                          {name:"Price"},
                          {name:"City"},
                          {name:"From", type:"date"},
                          {name:"To", type:"date"}]
                });
                
            //var table = $('#example1').DataTable();

            // Event listener to the two range filtering inputs to redraw on input
            $('#min, #max').keyup(function () {
                table.draw();
            });

            $('#name').keyup(function () {
                table.search( this.value ).draw();
            });


        }); 
