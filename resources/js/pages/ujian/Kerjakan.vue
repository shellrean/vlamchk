<template>
	<div>
		<loading :active.sync="isLoading" 
        :is-full-page="true"></loading>

		<div class="container exam" v-if="filleds">
			<div class="card">
				<div class="card-body">
					<div class="bar-top">
						<span>SOAL NOMOR</span>
						<div class="soal-title" id="page">{{ questionIndex+1 }}</div>
						<div id="page-count" style="display:none"></div>
						<div class="right">
							<div class="timer js-ujian">
								<div class="timer-label hidden-xs">Sisa Waktu</div>
								<div class="timer-time" id="timer">{{ prettyTime }}</div>
							</div>
							<b-button variant="primary" class="btn-soal" v-b-modal.nomorSoal>
								Daftar Soal<span class="fa fa-th"></span>
							</b-button>
						</div>
					</div>
					<div class="bar-text">
						<span>Ukuran Soal :
						</span>
						<a href="javascript:void(0)" class="font-small" id="font-small">A</a>
						<a href="javascript:void(0)" class="font-middle" id="font-middle">A</a>
						<a href="javascript:void(0)" class="font-big" id="font-big">A</a>
					</div>
					<div class="soal-wrapper" id="content">
						<table class="table table-borderless table-sm">
				    		<tr v-if="audio != ''">
				    			<td colspan="2">
				    				<audio-player :file="'http://192.168.0.200/storage/audio/'+audio"></audio-player>
				    			</td>
				    		</tr>
				    		<tr>
				    			<td colspan="2" v-html="filleds[questionIndex].soal.pertanyaan"></td>
				    		</tr>
				    		<tr v-for="(jawab,index) in filleds[questionIndex].soal.jawabans" :key="index">
				    			<td width="50px">
				    				<b-form-radio size="lg" v-model="selected" name="jwb" :value="jawab.id"  @change="selectOption(index)">
				    					<span class="text-uppercase">{{ index | charIndex }}</span>.
				    				</b-form-radio>
				    			</td>
				    			<td v-html="jawab.text_jawaban"></td>
				    		</tr>
				    		<tr v-if="filleds[questionIndex].soal.tipe_soal == 2">
				    			<td>
				    				<textarea class="form-control" placeholder="Tulis jawaban disini..." v-model="filleds[questionIndex].jawab_essy" @keyup="inputJawabEssy" style="height: 150px"></textarea>
				    			</td>
				    		</tr>
				    	</table>
					</div>
					<div class="button-wrapper">
						<b-button variant="primary" class="sebelum" size="md" @click="prev()" v-if="questionIndex != 0" :disabled="isLoadinger">
							<span class="fa fa-chevron-circle-left"></span>
							<b-spinner small type="grow" v-show="isLoadinger"></b-spinner> Sebelumnya
						</b-button>

						<button id="soal-ragu" class="btn btn-warning ml-auto">
							<b-form-checkbox size="lg" value="1" v-model="ragu">Ragu ragu</b-form-checkbox>
						</button>
						<b-button variant="primary" class="sesudah" size="md" :disabled="isLoadinger" @click="next()" v-if="questionIndex+1 != filleds.length">
							<b-spinner small type="grow" v-show="isLoadinger"></b-spinner>
							Selanjutnya <span class="fa fa-chevron-circle-right"></span>
						</b-button>
		    			<b-button variant="success" class="sesudah" size="md" @click="$bvModal.show('modal-selesai')" v-if="questionIndex+1 == filleds.length && checkRagu() == false" :disabled="isLoadinger">
		    				SELESAI <span class="fa fa-check-circle"></span>
		    			</b-button>
		    			<b-button variant="danger" class="sesudah" size="md" v-b-modal.modal-1 v-if="questionIndex+1 == filleds.length && checkRagu() == true">
		    				SELESAI <span class="fa fa-check-circle"></span>
		    			</b-button>
					</div>
				</div>
			</div>
		</div>
		<b-modal id="modal-selesai">
		    <template v-slot:modal-header="{ close }">
		      <h5>Konfirmasi</h5>
		    </template>

		    <template v-slot:default="{ hide }">
			  <b-form-checkbox size="lg" v-model="isKonfirm">Saya sudah selesai mengerjakan</b-form-checkbox>
		    </template>

		    <template v-slot:modal-footer="{ cancel }">
		      <b-button size="sm" variant="success" @click="selesai()" :disabled="!isKonfirm">
		        Selesai
		      </b-button>
		      <b-button size="sm" variant="danger" @click="cancel()">
		        Cancel
		      </b-button>
		    </template>
		 </b-modal>
		<b-modal id="nomorSoal" centered title="Nomor Soal" size="lg" hide-backdrop>
			<template v-slot:modal-footer="{ cancel }">
		      <b-button size="sm" variant="light" @click="cancel()">
		        Close
		      </b-button>
		    </template>
		    <template v-slot:default="{ hide }">
			  			<ul class="nomor-soal" id="nomor-soal">
						<button type="button" class="btn my-1 rounded-0 w-2 mx-1" v-for="(fiel,index) in filleds" :key="index" :class="{
							'btn-primary' : (fiel.jawab != 0), 
							'btn-outline-primary' : (fiel.jawab == 0), 
							'btn-warning' : (fiel.ragu_ragu == 1),
							'btn-dark text-light' : (index == questionIndex)}" @click="toLand(index)" :disabled="isLoadinger">
						  {{ index+1 }} 
						</button>
						</ul>
		    </template>
		</b-modal>
	</div>
</template>
<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import { mapActions, mapState, mapGetters, mapMutations} from 'vuex'
import AudioPlayer from '../../components/AudioPlayer.vue'

export default {
	name: 'DataUjian',
	created() {
		this.getAllSoal()
		this.start()
	},
	components: {
		AudioPlayer,
		Loading
	},
	data() {
		return {
			questionIndex: 0,
			selected: '',
			patt: 17,
			sidebar: false,
			ragu: '',
			time: 0,
			isKonfirm : false,
			interval: '',
			audio: ''
		}
	},
	filters: {
		charIndex(i) {
			return String.fromCharCode(97 + i)
		}
	},
	computed: {
		...mapGetters(['isAuth','isLoading','isLoadinger']),
		...mapMutations(['CLEAR_ERRORS','SET_LOADING']),
		...mapState('banksoal', { soals: state => state.ujian.data }),
		...mapState('ujian',{ 
			jawabanPeserta: state => state.jawabanPeserta,
			filleds: state => state.filledUjian.data,
			detail: state => state.filledUjian.detail
		}),
		...mapState('user', {
        	peserta: state => state.pesertaDetail
      	}),
      	prettyTime () {
			let sec_num = parseInt(this.time, 10)
    		let hours   = Math.floor(sec_num / 3600)
    		let minutes = Math.floor((sec_num - (hours * 3600)) / 60)
    		let seconds = sec_num - (hours * 3600) - (minutes * 60)
    		return hours+':'+minutes+':'+seconds
		}
	},
	methods: {
		...mapActions('banksoal', ['getUjian']),
		...mapActions('ujian', ['submitJawaban','submitJawabanEssy','takeFilled','updateWaktuSiswa','updateRaguJawaban','selesaiUjianPeserta']),
		getAllSoal() {
			this.getUjian({
				banksoal: this.$route.params.banksoal,
				peserta: localStorage.getItem('id')
			})
			.then((resp) => {
				
			})
			.catch(() => {
				this.$notify({
                  group: 'foo',
                  title: 'Error',
                  type: 'error',
                  text: 'Terjadi Kesalahan (Error: A.1).'
                })
			})
		},
		filledAllSoal() {
			const payld = {
				peserta_id: this.peserta.id,
				banksoal: this.$route.params.banksoal,
				jadwal_id: this.$route.params.jadwal_id
			}
			this.takeFilled(payld) 
			.then((resp) => {

			})
			.catch(() => {
				this.$notify({
                  group: 'foo',
                  title: 'Error',
                  type: 'error',
                  text: 'Terjadi Kesalahan (Error: A.2).'
                })
			})
		},
		updateSisaWaktu(time) {
			this.updateWaktuSiswa({ 
				sisa_waktu: time,
				peserta_id: this.peserta.id,
				jadwal_id: this.$route.params.jadwal_id
			})
			.then((resp) => {

			})
			.catch(() => {
				this.$notify({
                  group: 'foo',
                  title: 'Error',
                  type: 'error',
                  text: 'Terjadi Kesalahan (Error: A.3).'
                })
			})
		},
		selectOption(index) {
			const fill = this.filleds[this.questionIndex]

	        this.submitJawaban({ 
	        	jawaban_id : this.filleds[this.questionIndex].id,
	        	jawab : this.filleds[this.questionIndex].soal.jawabans[index].id,
	        	correct: this.filleds[this.questionIndex].soal.jawabans[index].correct,
	        	index : this.questionIndex
	        })
			.catch(() => {
				this.$notify({
                  group: 'foo',
                  title: 'Error',
                  type: 'error',
                  text: 'Sepertinya anda terputus dari server (Error: A.4).'
                })
			})
		},
		raguRagu(val) {
			this.updateRaguJawaban({
				ragu_ragu: val,
				index: this.questionIndex,
				jawaban_id : this.filleds[this.questionIndex].id
			})
		},
		selesai() {
			this.selesaiUjianPeserta({
				peserta_id : this.peserta.id,
				jadwal_id : this.detail.jadwal_id
			})
			this.$router.push({ name: 'ujian.selesai' })
			clearInterval(this.interval); 
		},
		prev() {
		    if (this.filleds.length > 0) this.questionIndex--
		},
		next() {
			if (this.questionIndex < this.filleds.length) this.questionIndex++
		},
		toggle() {
	    	this.sidebar = !this.sidebar;
	    },
	    toLand(index) {
	    	this.questionIndex = index
	    },
	    start () {
			this.timer = setInterval( () => {
				if (this.time > 0) {
					 this.time--
				} else {

				}
			}, 1000 )
		},
		checkRagu() {
			let ragger = 0
			this.filleds
			.filter(function(element) {
			    if (element.ragu_ragu == "1") {
			       ragger++
			    }
			})

			if (ragger > 0) {
				return true
			}
			return false
		},
		inputJawabEssy(val) {
			const fill = this.filleds[this.questionIndex]

	        this.submitJawabanEssy({ 
	        	jawaban_id : this.filleds[this.questionIndex].id,
	        	index : this.questionIndex,
	        	essy: fill.jawab_essy
	        })
		}
	},
	watch: {
		soals(val) {
			this.filledAllSoal()
		},
		questionIndex() {
			this.selected = this.filleds[this.questionIndex
			].jawab
			this.ragu = this.filleds[this.questionIndex].ragu_ragu
			if(this.filleds[this.questionIndex].soal.audio != null) {
				this.audio = this.filleds[this.questionIndex].soal.audio
			} 
			else {
				this.audio = ''
			}
		},
		filleds() {
			this.selected = this.filleds[this.questionIndex].jawab
			this.ragu = this.filleds[this.questionIndex].ragu_ragu
			if(this.filleds[this.questionIndex].soal.audio != null) {
				this.audio = this.filleds[this.questionIndex].soal.audio
			} 
			else {
				this.audio = ''
			}
		},
		detail(val) {
			this.time = val.sisa_waktu
			this.interval = setInterval( () => {
				if (this.time > 0) {
					// this.updateSisaWaktu(this.time)
				} else {
					this.selesai()
				}
			}, 5000 )
		}, 
		ragu(val) {
			if (val == false) {
				const set = 0
				this.raguRagu(set)
			}

			this.raguRagu(val)
		}
	},
	mounted() {
		window.addEventListener("keypress", e => {
			console.log(e.keyCode);
		});
	}
}
</script>