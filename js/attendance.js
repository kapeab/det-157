/**
 * Shows the user all of the users marked as present for a given event.
 */
function view_attendance() {
    window.location.href = '/index.php/attendance/attendees/' + $('#event').val();
}

/**
 * Checks to make sure there isn't already an attendance record for the user trying to submit the memo.
 *
 * @param event_id The id of the event to check
 */
function check_memo_user_event(event_id) {
    if(event_id !== '')
    {
        $.ajax({
            url: '/index.php/attendance/status/' + $('#user_id').val() + '/' + event_id,
            method: 'post',
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if(response !== null)
                {

                }
            },
            error: function (response) {
                console.log(response);
                console.log("Error: There was a problem checking to see if there is an attendance record for the logged " +
                    "in user for " + $('#select_event option:selected').text());
                alert("Error: There was a problem checking to see if there is an attendance record for the logged in " +
                    "user for " + $('#select_event option:selected').text());
            }
        });
    }
}

