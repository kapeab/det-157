<script src="<?php echo base_url("js/group.js"); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url("css/group.css"); ?>">

<div class="jumbotron container-fluid">
    <div class="container">
        <h1 class="display-4"> Create Group </h1>
        <div class="card" id="Edit Groups">
            <div class="card-body">

                <?php echo form_open('group/add'); ?>
                <h5 class="card-text">Create Group</h5>
                <div class="form-group">
                    <label for="groupname">Group Label:</label>
                    <input type="text" name="label" id="groupname" class="form-control" placeholder="Enter the group's label" required>
                </div>

                <div class="form-group">
                    <label>Group Description:</label>
                    <input type="text" name="description" id="groupdes" class="form-control" placeholder="What other people see as the group name" required>
                </div>

                <button class="btn btn-success" type="submit" name="submit">Create Group</button>
                </form>
                <br>

                <h5 class="card-text">Modify Group</h5>
                <div class="form-group">
                    <label for="selectgroup">Select Group</label>
                    <select id="selectgroup" name="group" onchange="select(this.value)" id="members" class="form-control" required>
                        <option value="">Choose...</option>
                        <?php
                        foreach( $groups as $group )
                        {
                            echo "<option value='" . $group->id . "'> " . $group->name . "</option>";
                        }
                        ?>
                    </select>
                </div>


                <?php echo form_open('group/addmembers', array('id' => 'add')); ?>
                <label for="ngroupmember">Add Members</label>
                <div class="selectcadets" id="ngroupmember">
                </div><br>
                <button class="btn btn-warning" type="submit" name="submit">Add Members</button>
                </form><br>

                <?php echo form_open('group/removemembers', array('id' => 'remove')); ?>
                <label for="groupmember">Remove Members</label>
                <div class="selectcadets" id="groupmember">
                </div><br>
                <button class="btn btn-warning" type="submit" name="submit">Remove Members</button>
                </form><br>

                <!--          TODO: Add an are you sure pop up to this action -->
                <h5 class="card-text">Delete Group</h5>
                <?php echo form_open('group/remove'); ?>
                <div class="form-group">
                    <label for="selectgroup">Select Group</label>
                    <select id="selectgroup" name="group" class="form-control" required>
                        <option value="">Choose...</option>
                        <?php
                        foreach( $groups as $group )
                        {
                            // Doesn't allow admin or general members groups to be deleted
                            if( $group->name !== "admin" && $group->name !== "members" )
                            {
                                echo "<option value='" . $group->id . "'> " . $group->name . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <button class="btn btn-danger" type="submit" name="submit">Delete Group</button>
                </form><br>
            </div>
        </div>
    </div>
</div>
