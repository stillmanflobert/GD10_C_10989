package com.example.gd10_kelas_10989

import android.app.AlertDialog
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Toast
import com.example.gd10_kelas_10989.databinding.ActivityDetailMahasiswaBinding
import com.example.gd10_kelas_10989.fragment.MahasiswaData
import com.example.gd10_kelas_10989.fragment.RClient
import com.example.gd10_kelas_10989.fragment.ResponseCreate
import com.example.gd10_kelas_10989.fragment.ResponseDataMahasiswa
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class DetailMahasiswaActivity : AppCompatActivity() {

    private lateinit var binding : ActivityDetailMahasiswaBinding
    private var b:Bundle? = null
    private val listMahasiswa = ArrayList<MahasiswaData>()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityDetailMahasiswaBinding.inflate(layoutInflater)
        setContentView(binding.root)

        b = intent.extras
        val nim = b?.getString("nim")
        nim?.let { getDataDetail(it) }
        binding.btnHapus.setOnClickListener {
            nim?.let { it1 -> deleteData(it1) }
        }

        binding.btnEdit.setOnClickListener {
            startActivity(
                Intent(this,FormEditMahasiswaActivity::class.java).apply {
                    putExtra("nim",nim)
                })
        }

    }

    fun getDataDetail(nim:String){
        RClient.instances.getData(nim).enqueue(object : Callback<ResponseDataMahasiswa> {
            override fun onResponse(
                call: Call<ResponseDataMahasiswa>,
                response: Response<ResponseDataMahasiswa>
            ) {
                if(response.isSuccessful){
                    response.body()?.let {
                        listMahasiswa.addAll(it.data) }
                    with(binding) {
                        tvNim.text = listMahasiswa[0].nim
                        tvNama.text = listMahasiswa[0].nama
                        tvAlamat.text = listMahasiswa[0].alamat
                        tvProdi.text = listMahasiswa[0].prodi
                        tvTgllahir.text = listMahasiswa[0].tgllhr
                    }
                }
            }
            override fun onFailure(call: Call<ResponseDataMahasiswa>, t: Throwable) {
            }
        })
    }
    override fun onRestart() {
        super.onRestart()
        this.recreate()
    }
    fun deleteData(nim:String){
        val builder = AlertDialog.Builder(this@DetailMahasiswaActivity)
        builder.setMessage("Anda Yakin mau hapus?? Saya nggayakin loh.")
            .setCancelable(false)
            .setPositiveButton("Ya, Hapus Aja!"){dialog, id->
                doDeleteData(nim)
            }
            .setNegativeButton("Tidak, Masih sayangdataku"){dialog,id ->
                dialog.dismiss()
            }
        val alert = builder.create()
        alert.show()
    }

    private fun doDeleteData(nim:String) {
        RClient.instances.deleteData(nim).enqueue(object: Callback<ResponseCreate>{
            override fun onResponse(
                call: Call<ResponseCreate>,
                response: Response<ResponseCreate>
            ) {
                if (response.isSuccessful) {
                    Toast.makeText(applicationContext, "Databerhasil dihapus", Toast.LENGTH_LONG).show()
                    finish()
                }
            }
            override fun onFailure(call: Call<ResponseCreate>, t:Throwable) {
            }
        })
    }


}