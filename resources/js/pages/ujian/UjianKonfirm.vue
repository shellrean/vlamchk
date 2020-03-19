<template>
	<div class="container">
		<div class="card card-bg" style="max-width: 600px">
			<div class="card-body">
				<h4>Konfirmasi Data Peserta</h4>
				<form id="fmToken" name="fmToken" @submit.prevent="cekToken" class="form-custom form-ajax">
					<div class="form-group">
						<label for="nisn">NO UJIAN</label>
						<p class="form-control-static" v-text="peserta.no_ujian"></p>
					</div>
					<div class="form-group">
						<label for="nama">Nama Peserta</label>
						<p class="form-control-static" v-text="peserta.nama"></p>
					</div>
					<div class="form-group">
						<label for="nm_uji">Mata Ujian</label>
						<p class="form-control-static" v-if="jadwal && ujian" v-text="jadwal.matpel"></p>
						<p class="form-control-static" v-if="!ujian">Tidak ada jadwal ujian pada hari ini</p>
						<span class="line"></span>
					</div>
					<div class="form-group" v-if="jadwal && ujian && ujian.status_ujian != '3'">
						<label for="token">Token</label>
						<input type="text" class="form-control" autofocus="" placeholder="Masukkan token" v-model="token_ujian">
						<span class="line"></span>
						<small class="text-danger" v-if="invalidToken.token">Token tidak sesuai dengan pusat</small>
						<small class="text-danger" v-if="invalidToken.release">Status token belum dirilis</small>
					</div>
					<div class="form-group" v-if="jadwal && ujian && ujian.status_ujian != '3'">
						<button type="submit" class="btn btn-primary w-100 btn-form-ajax" :disabled="isLoading">
							<b-spinner small type="grow" v-show="isLoading"></b-spinner>Mulai
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</template> 
<script>
	import Loading from 'vue-loading-overlay';
	import 'vue-loading-overlay/dist/vue-loading.css';
	import { mapActions, mapState,mapGetters, mapMutations } from 'vuex'
	export default {
		name: 'KonfirmUjian',
		components: {
			Loading
		},
	    data() {
	      return {
	        token_ujian : '',
	        timeout: 0
	      } 
	    },
	    computed: {
	    	...mapGetters(['isAuth','isLoading']),
	    	...mapState('jadwal', {
	    		jadwal: state => state.banksoalAktif.data
	    	}),
	    	...mapState('user', {
		        peserta: state => state.pesertaDetail
		     }),
	    	...mapState('ujian', {
	    		ujian: state => state.dataUjian.data,
	    		invalidToken: state => state.invalidToken
	    	})
	    },
	    methods: {
	      ...mapActions('ujian',['getPesertaDataUjian','tokenChecker']),
	      cekToken(){
	      	this.tokenChecker({
	      		token: this.token_ujian
	      	})
	      	.then(() => {
	      		this.$router.push({ name: 'ujian.prepare' })
	      	})
	      	.catch(() => {
	      		
	      	})
	      },
	      dataUjianPeserta() {
	      	this.getPesertaDataUjian({
	      		jadwal_id 	:this.jadwal.jadwal.id,
	      		peserta_id 	: this.peserta.id,
	   			lama		: this.jadwal.jadwal.lama
	      	})
	      }
	    }
	}
</script>