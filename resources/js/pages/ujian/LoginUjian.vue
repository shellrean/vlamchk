<template>
	<div class="wrapper">
		<div class="bg"><img src="/unbk/img/bg-header.png"></div>
		<div class="container">
			<div class="header">
				<div class="logo logo-center">
					<img src="/unbk/img/logo-white.png">
					<div>
						<h2>PUSPENDIK</h2>
						<h6>CBT Application</h6>
					</div>
				</div>
			</div>
			<div class="card login">
				<div class="card-body">
					<h4>Selamat Datang</h4>
					<p>Silahkan login dengan username dan password yang anda miliki</p>
					<form class="auth-form" @submit.prevent="postLogin">
						<div class="form-label-group">
							<span class="fa fa-user"></span>
							<input type="text" autofocus="" class="form-control active" :class="{ 'is-invalid' : errors.no_ujian }" v-model="data.no_ujian" placeholder="No peserta" required @keyup="clearError"/>
							<div class="invalid-feedback" v-if="errors.no_ujian">{{ errors.no_ujian[0] }}</div>
							<span class="line"></span>
						</div>
						<div class="form-label-group">
							<span class="fa fa-lock"></span>
							<input type="password" class="form-control":class="{ 'is-invalid' : errors.password }"placeholder="Password" v-model="data.password" required @keyup="clearError"/>
							<div class="invalid-feedback" v-if="errors.password">{{ errors.password[0] }} </div>
							<p v-if="errors" class="text-danger" v-text="errors.invalid"></p>
							<span class="line"></span>
						</div>
						<b-button variant="primary" size="lg" block  :disabled="isLoading" type="submit">
							<b-spinner small type="grow" v-show="isLoading"></b-spinner>&nbsp;Login
						</b-button>
					</form>
				</div>
				<div class="card-footer">

				</div>
			</div>
		</div>
    </div>
</template>
<script>
	import { mapActions, mapMutations, mapGetters, mapState } from 'vuex'
	export default {
		data() {
			return {
				data: {
					no_ujian: '',
					password: ''
				}
			}
		},
		created() {
			if (this.isAuth) {
				this.$router.push({ name: 'ujian.konfirm' })
			}
		},
		computed: {
			...mapGetters(['isAuth','isLoading']),
			...mapState(['errors'])
		},
		methods: {
			...mapActions('auth',['submit']),
			...mapMutations(['CLEAR_ERRORS','SET_LOADING']),
			postLogin() {
				this.SET_LOADING(true)
				this.submit(this.data).then( () => {
					if (this.isAuth) {
						this.CLEAR_ERRORS()
						this.$router.push({ name: 'ujian.konfirm' })
					}
				})
			},
			clearError() {
				this.CLEAR_ERRORS()
			}
		}
	}
</script>