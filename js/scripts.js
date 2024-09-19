
$(document).ready(function(){
    // Add a new row
    $('.add-row-btn').click(function(){
        var newRow = `
            <tr data-id="">
                <td><input type="text" name="fruit_name" class="fruit-input"></td>
                <td><input type="text" name="veggies" class="veggies-input"></td>
                <td><input type="text" name="size" class="size-input"></td>
                <td class="pricewrap"><span>$</span><input type="text" name="price" class="price-input"></td>
                <td><button class="remove-row-btn remove-btn">✕</button></td>
                <input type="hidden" class="row-id" value="">
            </tr>`;
        $('#fruitsTable tbody').append(newRow);
    });

    // Remove row
    $(document).on('click', '.remove-btn-fruits', function(){
        var row = $(this).closest('tr');
        var rowId = row.find('.row-id').val();

        // Confirm deletion
        var confirmDelete = confirm('Are you sure you want to delete this fruit?');
        
        if (confirmDelete) {
            if (rowId) {
                $.ajax({
                    url: 'delete_fruits.php', // A PHP script to handle the deletion
                    method: 'POST',
                    data: { id: rowId },
                    success: function(response){
                        var result = JSON.parse(response);
                        if (result.success) {
                            row.remove();
                            alert('Row deleted successfully!');
                        } else {
                            alert('Error deleting row: ' + result.error);
                        }
                    },
                    error: function() {
                        alert('An error occurred while deleting the row.');
                    }
                });
            } else {
                row.remove(); // If the row is not saved in the DB yet, just remove it
            }
        }
    });


    // Save row on input change
    $(document).on('input', '.fruit-input, .veggies-input, .size-input, .price-input', function(){
        var row = $(this).closest('tr');
        var data = {
            id: row.find('.row-id').val(),
            fruit_name: row.find('.fruit-input').val(),
            veggies: row.find('.veggies-input').val(),
            size: row.find('.size-input').val(),
            price: row.find('.price-input').val(),
            action: 'fruits'
        };

        $.ajax({
            url: 'save_fruits.php', // Your PHP script
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(response){
                if (response.success) {
                    row.find('.row-id').val(response.id); // Update row ID if a new row was created
                    row.attr('data-id', response.id); // Update data-id attribute for future reference
                    console.log('Data saved successfully!');
                } else {
                    console.log('Error saving data: ' + response.error);
                }
            }
        });
    });
});


//vegetables-----------vegetables-------------vegetables-----------vegetables

$(document).ready(function(){
    // Add a new row
    $('.add-row-btn-veg').click(function(){
        var tableId = $(this).data('table');
        var newRow = `
            <tr data-id="">
                <td><input type="text" name="vegetable_name" class="vegetable-input"></td>
                <td><input type="text" name="size" class="size-input"></td>
                <td class="pricewrap"><span>$</span><input type="text" name="price" class="price-input"></td>
                <td><button class="remove-row-btn remove-btn">✕</button></td>
                <input type="hidden" class="row-id" value="">
            </tr>`;
        $('#' + tableId + ' tbody').append(newRow);
    });

    // Remove row with confirmation
    $(document).on('click', '.remove-row-btn-vegetables', function(){
        var row = $(this).closest('tr');
        var rowId = row.find('.row-id').val();

        if (confirm('Are you sure you want to delete this vegetable?')) {
            if (rowId) {
                $.ajax({
                    url: 'delete_vegetable.php', // PHP script for deleting vegetables
                    method: 'POST',
                    data: { id: rowId },
                    success: function(response){
                        var result = JSON.parse(response);
                        if (result.success) {
                            row.remove();
                            alert('Row deleted successfully!');
                        } else {
                            alert('Error deleting row: ' + result.error);
                        }
                    },
                    error: function() {
                        alert('An error occurred while deleting the row.');
                    }
                });
            } else {
                row.remove(); // Remove from DOM if not yet saved
            }
        }
    });

    // Save row on input change
    $(document).on('input', '.vegetable-input, .size-input, .price-input', function(){
        var row = $(this).closest('tr');
        var data = {
            id: row.find('.row-id').val(),
            vegetable_name: row.find('.vegetable-input').val(),
            size: row.find('.size-input').val(),
            price: row.find('.price-input').val(),
            action: 'vegetables'
        };

        $.ajax({
            url: 'save_vegetable.php', // PHP script for saving vegetables
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(response){
                if (response.success) {
                    row.find('.row-id').val(response.id); // Update row ID if newly created
                    row.attr('data-id', response.id); // Update data-id attribute
                    console.log('Data saved successfully!');
                } else {
                    console.log('Error saving data: ' + response.error);
                }
            }
        });
    });
});
