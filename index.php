<?php
include_once "header.html";
include_once "connection.php";
include_once "classAnime.php";
?>

<div class = "container__addAnime">
    <form action="" method="POST" enctype="multipart/form-data" id = "anime_form">
        <div class = "addAnime">
            <div class="form__group field">
                <input type="text" class="form__field" placeholder="Name" name="name" id='name'/>
                <label for="name" class="form__label">Name</label>
            </div>
            <div class="form__group field">
                <input type="text" class="form__field" placeholder="yearOfRelease" name="yearOfRelease" id='yearOfRelease' />
                <label for="yearOfRelease" class="form__label">yearOfRelease</label>
            </div>
            <div class="form__group field">
                <input type="text" class="form__field" placeholder="genre" name="genre" id='genre' />
                <label for="genre" class="form__label">genre</label>
            </div>
            <div class="form__group field">
                <input type="text" class="form__field" placeholder="type" name="type" id='type' />
                <label for="type" class="form__label">type</label>
            </div>
            <div class="form__group field">
                <input type="text" class="form__field" placeholder="numberOfEpisodes" name="numberOfEpisodes" id='numberOfEpisodes' />
                <label for="numberOfEpisodes" class="form__label">numberOfEpisodes</label>
            </div>
            <div class="form__group field">
                <input type="text" class="form__field" placeholder="rating" name="rating" id='rating' />
                <label for="rating" class="form__label">rating</label>
            </div>
            <div class="form__group field">
                <input type="text" class="form__field" placeholder="description" name="description" id='description' />
                <label for="description" class="form__label">description</label>
            </div>
            <div class="form__group field" >
                <input type="file"  multiple accept="image/png, image/jpeg" class="form__field" placeholder="image" name="image[]" id='image'/>
                <label for="image" class="form__label">image</label>
            </div>
        </div>
        <div class = "photo_arr" id = "onTimeImage"></div>
    </form>
    <div class="container__button">
        <div class="center">
            <button class="btn" id = "btn-form">
                <svg width="180px" height="60px" viewBox="0 0 180 60" class="border">
                    <polyline points="179,1 179,59 1,59 1,1 179,1" class="bg-line" />
                    <polyline points="179,1 179,59 1,59 1,1 179,1" class="hl-line" />
                </svg>
                <span>Отправить</span>
            </button>
        </div>
    </div>
</div>
    <script type="text/javascript">
        $(document).ready(function() {
            function showMessage(message, type) {
                let messageElement = document.createElement("div");
                messageElement.classList.add("message", type);
                messageElement.textContent = message;
                document.body.appendChild(messageElement);
                setTimeout(function() {
                    messageElement.remove();
                }, 5000);
            }

            let fields = ['#name', '#yearOfRelease', '#genre', '#type', '#numberOfEpisodes', '#rating', '#description'];
            function checkFields() {
                let allFieldsFilled = true;
                $.each(fields, function(index, field) {
                    if ($(field).val() == "") {
                        $(field).css('border', '1px solid red');
                        allFieldsFilled = false;
                    } else {
                        $(field).css('border', '');
                    }
                });
            }

            let arr = [];
            $('#image').on('change', function() {
                let files = $(this)[0].files;
                if (files.length > 0) {
                    let formData = new FormData();
                    for (let i = 0; i < files.length; i++) {
                        let file = files[i];
                        arr.push(file);
                        formData.append('image[]', file);
                    }
                    $.ajax({
                        url: 'saveImages.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            const obj = JSON.parse(response);
                            for (let i = 0; i < obj["images"].length; i++) {
                                let div = document.createElement('div');
                                div.className = "timeImages";
                                div.setAttribute("id", "timeImages");
                                let img = new Image();
                                img.src = obj["images"][i];
                                img.alt = "Your Photo";
                                div.appendChild(img);
                                let buttonClose = document.createElement('div');
                                buttonClose.className = "button__close";
                                div.appendChild(buttonClose);
                                let thumbX = document.createElement('div');
                                thumbX.className = "thumb__x";
                                thumbX.setAttribute("id", "thumb__x");
                                //thumbX.setAttribute("onclick", "removeImg()");
                                buttonClose.appendChild(thumbX);
                                document.getElementById("onTimeImage").appendChild(div);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error uploading images: ' + error);
                        }
                    });
                }
            });

            $('#btn-form').on('click', function() {
                let mainForm = new FormData();
                if (!checkFields()) {
                for (let i = 0; i < arr.length; i++) {
                    let file = arr[i];
                    mainForm.append('image[]', file);
                }
                mainForm.append('name', $('#name').val());
                mainForm.append('yearOfRelease', $('#yearOfRelease').val());
                mainForm.append('genre', $('#genre').val());
                mainForm.append('type', $('#type').val());
                mainForm.append('numberOfEpisodes', $('#numberOfEpisodes').val());
                mainForm.append('rating', $('#rating').val());
                mainForm.append('description', $('#description').val());
                $.ajax({
                    url: 'process_form.php',
                    type: 'POST',
                    data: mainForm,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        arr = [];
                        const container = document.getElementById("onTimeImage");
                        while (container.firstChild) {
                            container.removeChild(container.firstChild);
                        }
                        document.getElementById('anime_form').reset();
                        showMessage("Отправка прошла успешно!", "success");
                    },
                    error: function (xhr, status, error) {
                        showMessage('Error uploading images: ' + error, "error");
                    }
                });
            } else {
                    console.log("error");
                }
            });
            $('#onTimeImage').on('click', '#thumb__x', function() {
                $(this).closest('.timeImages').remove();
                let index = $(this).data('index');
                arr.splice(index, 1);
            });
        });
    </script>
<?php
include_once "footer.html";
