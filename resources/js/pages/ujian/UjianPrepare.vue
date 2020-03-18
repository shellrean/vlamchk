<template>
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
				<div class="card card-bg" v-if="jadwal">
					<div class="card-body">
						<h4>Konfirmasi Tes</h4>
						<form id="fmTes" name="fmTes" method="POST"  class="form-custom form-ajax">
							<div class="form-group">
								<label>Mata Pelajaran</label>
								<p class="form-control-static">{{jadwal.matpel}}&nbsp;</p>
							</div>
							<div class="form-group">
								<label>Alokasi Waktu Tes</label>
								<p class="form-control-static">{{ Math.floor(jadwal.jadwal.lama / 60)}} Menit &nbsp;</p>
							</div>
							<div class="form-group">
								<label>Waktu mulai</label>
								<p class="form-control-static">{{mulai}}&nbsp;</p>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="card card-bg" v-if="jadwal">
					<div class="card-body">
						<p>Tombol MULAI hanya akan aktif apabila waktu sekarang sudah melewati waktu mulai tes</p>
						<button type="button" class="btn btn-primary w-100 rounded-pill btn-form-ajax" @click="start" :disabled="disable">MULAI</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import { mapActions, mapState } from 'vuex'
export default {
	name: 'PrepareUjian',
	created() {
		this.starTime()
	},
	data() {
		return {
			disable: true,
			time: '',
			starter: '',
			durasi: ''
		}
	},
	computed: {
		...mapState('jadwal', {
			jadwal: state => state.banksoalAktif.data,
			mulai: state => state.banksoalAktif.data.jadwal.mulai
		}),
		...mapState('user', {
		    peserta: state => state.pesertaDetail
		}),
	},
	methods: {
	    ...mapActions('ujian',['pesertaMulai']),
	    start() {
	    	this.pesertaMulai({
	    		peserta_id: this.peserta.id
	    	})
	    	this.$router.push({ 
	    		name: 'ujian.while', 
	    		params: { 
	    			banksoal: this.jadwal.banksoal_id, 
	    			jadwal_id: this.jadwal.jadwal.id
	    		} 
	    	})
	    },
	    starTime() {
			setInterval( () => {
				this.time = new Date()
			}, 1000 )
		}
	},
	watch: {
		time() {
			if(this.starter < this.time) {
				this.disable = false
			}
		}
	}
}
</script>