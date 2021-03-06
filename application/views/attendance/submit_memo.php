<link rel="stylesheet" type="text/css" href="/css/attendance.css">

<div class="shadow p-3 mb-5 bg-white rounded" style="margin: 5px;">
    <h1>Submit Memo</h1>
    <form action="/index.php/attendance/create_memo" enctype="multipart/form-data" method="POST">
        <?php
            if(isset($upload_errors))
            {
                echo '<div class="alert alert-warning" role="alert">';
                echo $upload_errors;
                echo '</div>';
            }
        ?>
        <input class="d-none" name="user_id" id="user_id" type="number" value="<?php
            $user = $this->ion_auth->user()->row();
            echo $user->id;
        ?>" required>
        <div class="form-group">
            <label for="select_event">Select Event</label>
            <select id="select_event" name="event" onchange="check_memo_user_event(this.value)" class="form-control">
                <option selected value="">No Event</option>
                <?php
                    foreach ($events as $event) {
                        echo '<option value="' . $event->id . '">' . $event->name . ' - ' . Date('Y-m-d', strtotime($event->date)) . '</option>';
                    }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="memo_type">Memo Reason</label>
            <select id="memo_type" name="memo_type" class="form-control" required>
                <option value="">Choose...</option>
                <?php
                    foreach ($memo_types as $memo_type) {
                        echo '<option value="' . $memo_type->id . '">' . $memo_type->label . '</option>';
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="memo_for">Submit Memo To</label>
            <select class="form-control" name="memo_for" id="memo_for" required>
                <option value="">Choose...</option>
                <?php
                    foreach( $users as $user )
                    {
                        if($user->rank === 'Cadet')
                        {
                            echo "<option value='" . $user->id . "'>Cadet " . $user->last_name . "</option>";
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="attachment">Memo Attachment (PDF Only)</label>
            <input type="file" class="form-control" name="attachment" id="attachment" required/>
        </div>
        <div class="form-group">
            <label for="comments">Additional Comments</label>
            <textarea id="comments" name="comments" class="form-control" rows="5" placeholder="This is optional..."></textarea>
        </div>
        <button type="submit" id="submit_memo" class="btn btn-primary">Submit Memo</button>
    </form>
</div>

<script type="text/javascript" src="/js/attendance.js"></script>
