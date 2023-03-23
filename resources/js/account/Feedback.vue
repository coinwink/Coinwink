
<script setup>
import LoadingSpinner from '../components/LoadingSpinner.vue';

import { ref, onMounted } from 'vue';

const props = defineProps ({
    showFeedback: Boolean
})

const emit = defineEmits(['showFeedback']);

let feedbackMessage = ref('');

let feedbackSuccess = ref(false);
let feedbackError = ref(false);
let feedbackPrep = ref(true);
let loaderFeedbackSubmit = ref(false);
let btnFeedbackSubmit = ref(true);

function resetFeedback() {
    feedbackMessage.value = '';
    feedbackSuccess.value = false;
    feedbackError.value = false;
    feedbackPrep.value = true;
    loaderFeedbackSubmit.value = false;
    btnFeedbackSubmit.value = true;
}


function closeFeedback () {
	console.log('close');
	emit('showFeedback', false);
	resetFeedback();
}

function submitFeedback(e) {
    e.preventDefault();
    
    // var formFeedback = jQuery(feedbackMessage.value).serialize();
    // var formData = jQuery(feedbackMessage.value).serializeArray();

    btnFeedbackSubmit.value = false;
    loaderFeedbackSubmit.value = true;

    axios({
        method: 'post',
        url: '/api/feedback',
        data: {feedback: feedbackMessage.value}
    })
    .then((response) => {
        if (response.data == 'success') {
            feedbackSuccess.value = true;
            feedbackError.value = false;
            feedbackPrep.value = false;

            userFeedbackFinish();
        }
        else {
            feedbackSuccess.value = false;
            feedbackError.value = true;
            feedbackPrep.value = false;

            userFeedbackFinish();
        }
    });

    function userFeedbackFinish() {

        jQuery(document).one("click", function() {
			closeFeedback();
        });

        jQuery(document).one('keydown', function(event) {
            if (event.key == "Escape") {
				closeFeedback();
            }
        });

        jQuery("#np-modal-close").focus();
    }

}

// onMounted(() => {
// 	jQuery(document).one('keydown', function(event) {
// 		if (event.key == "Escape") {
// 			console.log('close');
// 			emit('showFeedback', false);
// 			resetFeedback();
// 		}
// 	});
// })

</script>

<template>
    <div id="feedback-div" v-if="showFeedback">

        <div class="np-modal-window" id="cw-feedback-modal">
            <div class="cw-feedback">
                <div class="cw-feedback-inner">
                    <div class="np-modal-close" id="np-modal-close" title="Close" @click="closeFeedback()">✕</div>
                    <div id="cw-feedback-content">
                        <div id="feedback-prep" v-if="feedbackPrep">
                            <div style="height:15px;"></div>
                            <span style="color:#c5c5c5;">Your message</span>
                            <div style="height:25px;"></div>
                            <form @submit="submitFeedback" id="form-account-feedback">
                                <textarea name="feedback" class="cw-feedback-input" v-model="feedbackMessage" maxlength="5000" required minlength="4" autofocus></textarea>
                                <div style="height:20px;"></div>
                                
                                <input name="action" type="hidden" value="account_feedback" />

                                <button class="btn-feedback-submit" v-if="btnFeedbackSubmit" type="submit">
                                    Submit
                                </button>

                                <div v-if="loaderFeedbackSubmit" class="block-submit" style="margin-top:5px;">
                                    <LoadingSpinner :theme='"matrix"' />
                                </div>
                            </form>
                        </div>

                        <div v-if="feedbackSuccess">
                            <div style="height:170px;"></div>
                            We have received your feedback.
                            <br>
                            <br>
                            Thank you!
                            <div style="height:30px;"></div>
                        </div>

                        <div v-if="feedbackError">
                            <div style="height:140px;"></div>
                            Error!
                            <br>
                            <br>
                            The message was not submitted.
                            <br>
                            <br>
                            Please contact us by email:
                            <br>
                            feedback@coinwink.com
                            <div style="height:30px;"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>

	.np-modal-close {
		position: absolute;
		cursor: pointer;
		font-size: 22px;
		font-weight: bold;
		color: #777;    
		right: 10px;
		top: 9px;
		width: 30px;
		height: 30px;
	}

	.np-modal-close:hover {
		color: #999;
	}

	#np-modal-close:focus div {
		color: #999;
	}

	.np-modal-window {
		height: 100%;
		width: 100%;
		position: fixed;
		z-index: 999;
		left: 0;
		top: 0;
		background-color: rgb(0,0,0);
		background-color: rgba(0,0,0, 0.95);
		overflow-x: hidden;
		box-sizing: content-box;
		align-items: center;
	}

	.cw-feedback {
		color: white;
		font-size:14px;
		font-weight: normal;
		background-color: black;
		border: 1px solid #333;
		border-radius: 5px;
		height: auto;
		padding: 40px 15px 45px 15px;
		width: 560px;
        height: 485px;
		max-width: 95%;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -51%);
		text-align: center;
		box-shadow: 0 0 15px 5px rgba(0, 0, 0, 0.5);
	}

	.cw-feedback-input {
		margin-top: 10px;
		margin-bottom: 15px;
		/* background-color: #2b3845; */
		background-color: #151a1f;
		color: white;
		border: 1px solid #666;
		padding: 10px;
		font-size: 14px;
		font-family: inherit;
		max-width:400px;
		width:95%;
		height:240px;
		resize: none;
	}

	.cw-feedback-input:invalid {
		box-shadow: none;
	}

	.cw-feedback-input::-webkit-scrollbar {
		width: 10px;
	}

	.cw-feedback-input::-webkit-scrollbar-thumb {
		background-color: #555;
	}

	.cw-feedback-input::-webkit-scrollbar-thumb:hover {
		background-color: #555;
	}

	.cw-feedback-input:hover, .cw-feedback-input:focus {
		border: 1px solid #888!important;
		outline: 0;
	}

	.btn-feedback-submit {
		width:130px;
		height: 40px;
		/* padding-bottom:6px; */
		border:1px solid #666;
		cursor:pointer;
		margin:0 auto;
		font-size:15px;
		color:#999;
		border-radius: 5px;
		background-color: black;
		font-family: 'Montserrat', sans-serif;
		font-weight: bold;
	}

	.btn-feedback-submit:hover, .btn-feedback-submit:active {
		border:1px solid #999;
		color: #aeaeae;
		outline: none;
	}

	.btn-feedback-submit:focus {
		outline: none;
		border:1px solid #999;
		color: #999;
	}

</style>