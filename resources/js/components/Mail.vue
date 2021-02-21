<template>    
<div>
	<h1 class="custom-h1-title">Mail</h1>
	<notifications group="message" classes="add-task-notification"/>
	<div class="row">
		<div class="col-4">
			<form @submit.prevent="sendMail()" novalidate>
				<div class="form-group row" :class="{ 'has-error': formMail.errors.has('to') }">
					<label for="to" class="col-sm-2 col-form-label">To</label>
					<div class="col-sm-10">
						<input type="email" v-model="formMail.to"  name="to" class="form-control" id="to">
							<has-error :form="formMail" field="to"></has-error>
						</div>
					</div>
					<div class="form-group row" :class="{ 'has-error': formMail.errors.has('subject') }">
						<label for="subject" class="col-sm-2 col-form-label">Subject</label>
						<div class="col-sm-10">
							<input type="text" v-model="formMail.subject"  name="subject" class="form-control" id="subject">
								<has-error :form="formMail" field="subject"></has-error>
							</div>
						</div>
						<div class="form-group row" :class="{ 'has-error': formMail.errors.has('message') }">
							<label for="message" class="col-sm-2 col-form-label">Message</label>
							<div class="col-sm-10">
								<textarea rows="4" cols="50" v-model="formMail.message"  name="message" class="form-control" id="message"/>
								<has-error :form="formMail" field="message"></has-error>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-10">
								<button type="submit" class="btn btn-primary">Send</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
</template>   
<script>
export default {
    // Create a new form instance
    data() {
        return {
            formMail: new Form({
                to: '',
                subject: '',
                message: ''
            })
        }
    },
    methods: {
        async sendMail() {
            await this.formMail.post('api/send-mail').then(function() {});
            this.formMail.clear();
            this.formMail.reset();
            this.$notify({
                group: 'message',
                title: 'Notification',
                text: 'Message sent',
                duration: 2000,

            });
        },
    }
}
</script>