import $ from 'jquery';

class Like {
    constructor(){
        this.events();
    }

    events(){
        $(".like-box").on('click', this.ourClickDispatcher.bind(this));
    }

    ourClickDispatcher(e){
        var currentLikeBox = $(e.target).closest(".like-box");

        if(currentLikeBox.data('exists') == "yes"){
            this.deleteLike(currentLikeBox);
            
        }else{
            this.createLike(currentLikeBox);
        }
    }

    createLike(currentLikeBox){

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url + "/wp-json/university/v1/manageLike",
            data: {professorId: currentLikeBox.data('professor')},
            method: 'POST',
            success: (response) => {
                currentLikeBox.attr("data-exists", "yes")
                var likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10)
                likeCount++;
                currentLikeBox.find(".like-count").html(likeCount);
                currentLikeBox.attr("data-like", response);
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            }
        });
    }

    deleteLike(currentLikeBox){
        $.ajax({
            beforeSend: xhr => {
                xhr.setRequestHeader("X-WP-Nonce", universityData.nonce)
            },
            url: universityData.root_url + "/wp-json/university/v1/manageLike",
            data: {like: currentLikeBox.attr("data-like")},
            method: 'DELETE',
            success: (response) => {
                currentLikeBox.attr("data-exists", "no")
                var likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10)
                likeCount--
                currentLikeBox.find(".like-count").html(likeCount)
                currentLikeBox.attr("data-like", "")
                console.log({'success': response});
            
            },
            error: (response) => {
                console.log({'error': response});
            }
        });
    }
}

export default Like;