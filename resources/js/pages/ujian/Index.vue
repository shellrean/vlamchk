<template>
	<div>
    <div class="bg"><img src="/unbk/img/bg-header.png"></div>
      <div class="header mx-5 d-flex">
        <div class="logo">
            <img src="/unbk/img/logo-white.png">
            <div>
                <h2>PUSPENDIK</h2>
                <h6>CBT Application</h6>
            </div>
        </div>
        <div class="user">
          <div class="user-text">
            <h6>{{ peserta.nama }}</h6>
            <a href="javascript:;" @click="logout" id="btnLogout" class="btn btn-sm btn-white logout btnLogout">Logout</a>
          </div>
          <div class="user-icon">
              <span class="fa fa-user-graduate"></span>
          </div>
        </div>
      </div>
      <router-view></router-view>
  </div>
</template>
<script>
  import { mapState, mapActions } from 'vuex'

	export default {
		name: 'IndexUjian',
    created() {
      this.setPesertaDetail()
    },
    data() {
      return {

      } 
    },
    computed: {
      ...mapState('user', {
        peserta: state => state.pesertaDetail
      }),
      ...mapState('jadwal', {
        jadwal: state => state.banksoalAktif.data
      }),
    },
    methods: {
      ...mapActions('jadwal',['ujianAktif']),
      ...mapActions('user',['setPesertaDetail']),
      ...mapActions('auth',['logoutPeserta']),
      ...mapActions('ujian',['getPesertaDataUjian']),
      logout() { 
        return new Promise((resolve, reject) => {
            this.logoutPeserta({ no_ujian : localStorage.getItem('no_ujian') })
            .then(() => {
              localStorage.removeItem('token')
              localStorage.removeItem('no_ujian')
              localStorage.removeItem('nama')
              localStorage.removeItem('id')
              resolve()
            })
            .catch(() => {
              localStorage.removeItem('token')
              localStorage.removeItem('no_ujian')
              localStorage.removeItem('nama')
              localStorage.removeItem('id')
              resolve()
            })
        }).then(() => {
            this.$store.state.token = localStorage.getItem('token')
            this.$router.push('/login')
        })
      },
      dataUjianPeserta() {
        this.getPesertaDataUjian({
          jadwal_id   :this.jadwal.jadwal.id,
          peserta_id  : this.peserta.id,
        lama    : this.jadwal.jadwal.lama
        })
      }
    },
    watch: {
      peserta() {
        this.ujianAktif(this.peserta.id)
      },
      jadwal(val) {
         if(typeof(val) != 'undefined') {
            this.dataUjianPeserta()
          }
      }
    }
	}
</script>