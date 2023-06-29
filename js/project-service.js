const ProjectService = {
    init: function(){
        $('#addProjectForm').validate({
            submitHandler: function(form) {
                var entity = Object.fromEntries((new FormData(form)).entries());
                if (entity.id != 0){
                    // update method
                    var id = entity.id;
                    delete entity.id;
                    ProjectService.update(id, entity);
                }else{
                    // add method
                    ProjectService.add(entity);
                }
            }
        });
        ProjectService.list();
    },

    list: function(){
        $.ajax({
            url: "rest/projects",
            type: "GET",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
            },
            success: function(data) {
                $("#projects-list").html("");
                var html = "";
                for(let i = 0; i < data.length; i++){
                    html += `
             <div class="col-lg-3">
               <div class="card">
                 <div class="card-body">
                   <h5 class="card-title">`+ data[i].title +`</h5>
                   <h6>Description:</h6>
                   <p class="card-text">`+ data[i].description +`</p>
                   <hr>
                   <h6>GIT url:</h6>
                   <p class="card-text">`+ data[i].git_link +`</p>
                   <div class="btn-group" role="group">
                       <button type="button" class="btn btn-danger project-button" onclick="ProjectService.delete(`+data[i].id+`)">Delete</button>
                       <button type="button" class="btn btn-primary project-button" onclick="ProjectService.get(`+data[i].id+`)">Edit</button>
                     </div>
                       <button type="button" class="btn btn-success project-button" style="float: right;" onclick="alert('Ovdje se pokrece CI/CD pipeline za projekat `+data[i].title+`!')">Run</button>
                 </div>
               </div>
             </div>
             `;
                }
                $("#projects-list").html(html);
                ProjectService.clearForm();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //UserService.logout();
            }
        });
    },

    add: function(project){
      $.ajax({
        url: 'rest/projects',
        type: 'POST',
        beforeSend: function(xhr){
          xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
        },
        data: JSON.stringify(project),
        contentType: "application/json",
        dataType: "json",
        success: function(result) {
            ProjectService.list(); // perf optimization
            $("#addProjectModal").modal("hide");
            toastr.success("Note added!");
        },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
          console.log(errorThrown);
      }
      });
    },

    get: function(id){
        $('.project-button').attr('disabled', true);

        $.ajax({
            url: 'rest/projects/'+id,
            type: "GET",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
            },
            success: function(data) {
                $('#addProjectForm input[name="id"]').val(data.id);
                $('#addProjectForm input[name="title"]').val(data.title);
                $('#addProjectForm textarea[name="description"]').val(data.description);
                $('#addProjectForm input[name="git_link"]').val(data.git_link);
                $('#addProjectForm input[name="git_user"]').val(data.git_user);
                $('#addProjectForm input[name="git_password"]').val(data.git_password);
                $('#addProjectForm textarea[name="commands"]').val(data.commands);
                $('.project-button').attr('disabled', false);
                $('#addProjectModal').modal("show");
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(XMLHttpRequest.responseJSON.message);
                $('.project-button').attr('disabled', false);
            }});
    },

    update: function(id, entity){
        $.ajax({
            url: 'rest/projects/'+id,
            type: 'PUT',
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
            },
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function(result) {
                $("#note-list").html('<div class="spinner-border" role="status"> <span class="sr-only"></span>  </div>');
                ProjectService.list(); // perf optimization
                $("#addProjectModal").modal("hide");
                ProjectService.clearForm();
                toastr.info("Note updated!");
            }
        });
    },

    delete: function(id){
      var old_html = $("#projects-list").html();
      $('.project-'+id).remove();
      $.ajax({
        url: 'rest/projects/'+id,
        type: 'DELETE',
        beforeSend: function(xhr){
          xhr.setRequestHeader('Authorization', localStorage.getItem('token'));
        },
        success: function(result) {
            $("#projects-list").html('<div class="spinner-border" role="status"> <span class="sr-only"></span>  </div>');
            ProjectService.list();
            toastr.error("Note deleted!");
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
           $("#projects-list").html(old_html);
        }
      });
    },

    clearForm: function () {
        $('#addProjectForm input[name="id"]').val(0);
        $('#addProjectForm input[name="title"]').val('');
        $('#addProjectForm textarea[name="description"]').val('');
        $('#addProjectForm input[name="git_link"]').val('');
        $('#addProjectForm input[name="git_user"]').val('');
        $('#addProjectForm input[name="git_password"]').val('');
        $('#addProjectForm textarea[name="commands"]').val('');
    }
  }