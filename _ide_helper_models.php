<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id_log
 * @property int $id_lokasi
 * @property int $id_admin
 * @property string $status
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Models\User $admin
 * @property-read \App\Models\Lokasi $lokasi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalLog whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalLog whereIdAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalLog whereIdLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalLog whereIdLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalLog whereStatus($value)
 */
	class ApprovalLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_event
 * @property string $nama_event
 * @property int|null $id_lokasi
 * @property string|null $lokasi_event
 * @property string $jenis_event
 * @property string|null $deskripsi
 * @property string|null $banner
 * @property \Illuminate\Support\Carbon $tanggal_mulai
 * @property \Illuminate\Support\Carbon $tanggal_selesai
 * @property string $status
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $banner_url
 * @property-read \App\Models\Lokasi|null $lokasi
 * @property-read \App\Models\User $pembuat
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event mendatang()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereIdEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereIdLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereJenisEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLokasiEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereNamaEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_favorit
 * @property int $id_user
 * @property int $id_lokasi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Lokasi $lokasi
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorit whereIdFavorit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorit whereIdLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorit whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorit whereUpdatedAt($value)
 */
	class Favorit extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_foto
 * @property int $id_lokasi
 * @property string $file_foto
 * @property string|null $caption
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $url_foto
 * @property-read \App\Models\Lokasi $lokasi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FotoLokasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FotoLokasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FotoLokasi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FotoLokasi whereCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FotoLokasi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FotoLokasi whereFileFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FotoLokasi whereIdFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FotoLokasi whereIdLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FotoLokasi whereUpdatedAt($value)
 */
	class FotoLokasi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_kategori
 * @property string $nama_kategori
 * @property string|null $icon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lokasi> $lokasis
 * @property-read int|null $lokasis_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori whereIdKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori whereNamaKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kategori whereUpdatedAt($value)
 */
	class Kategori extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_kecamatan
 * @property string $nama_kecamatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kelurahan> $kelurahans
 * @property-read int|null $kelurahans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lokasi> $lokasis
 * @property-read int|null $lokasis_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan whereIdKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan whereNamaKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan whereUpdatedAt($value)
 */
	class Kecamatan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_kelurahan
 * @property int $id_kecamatan
 * @property string $nama_kelurahan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Kecamatan $kecamatan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lokasi> $lokasis
 * @property-read int|null $lokasis_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelurahan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelurahan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelurahan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelurahan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelurahan whereIdKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelurahan whereIdKelurahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelurahan whereNamaKelurahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelurahan whereUpdatedAt($value)
 */
	class Kelurahan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_lokasi
 * @property string $nama_tempat
 * @property int $id_kategori
 * @property string $alamat
 * @property int $id_kecamatan
 * @property int $id_kelurahan
 * @property float $latitude
 * @property float $longitude
 * @property string|null $deskripsi
 * @property string|null $jam_operasional
 * @property string|null $kontak
 * @property string|null $website
 * @property string $status_verifikasi
 * @property float $rating_avg
 * @property int $jumlah_review
 * @property int $jumlah_kunjungan
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ApprovalLog> $approvalLogs
 * @property-read int|null $approval_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $difavoritOleh
 * @property-read int|null $difavorit_oleh_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \App\Models\FotoLokasi|null $fotoUtama
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FotoLokasi> $fotos
 * @property-read int|null $fotos_count
 * @property-read \App\Models\Kategori $kategori
 * @property-read \App\Models\Kecamatan $kecamatan
 * @property-read \App\Models\Kelurahan $kelurahan
 * @property-read \App\Models\User $kontributor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pengaduan> $pengaduans
 * @property-read int|null $pengaduans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RiwayatKunjungan> $riwayatKunjungans
 * @property-read int|null $riwayat_kunjungans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi disetujui()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi kategori($idKategori)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereIdKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereIdKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereIdKelurahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereIdLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereJamOperasional($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereJumlahKunjungan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereJumlahReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereKontak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereNamaTempat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereRatingAvg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereStatusVerifikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereWebsite($value)
 */
	class Lokasi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_pengaduan
 * @property int $id_user
 * @property int|null $id_lokasi
 * @property string $jenis_pengaduan
 * @property string $isi_pengaduan
 * @property string|null $foto_bukti
 * @property string $status
 * @property string|null $catatan_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $foto_bukti_url
 * @property-read \App\Models\Lokasi|null $lokasi
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereCatatanAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereFotoBukti($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereIdLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereIdPengaduan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereIsiPengaduan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereJenisPengaduan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengaduan whereUpdatedAt($value)
 */
	class Pengaduan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_review
 * @property int $id_lokasi
 * @property int $id_user
 * @property int $rating
 * @property string|null $komentar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Lokasi $lokasi
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereIdLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereIdReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereKomentar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereUpdatedAt($value)
 */
	class Review extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_riwayat
 * @property int $id_user
 * @property int $id_lokasi
 * @property string $status
 * @property float|null $latitude_arrived
 * @property float|null $longitude_arrived
 * @property float|null $jarak_tempuh Jarak tempuh dalam meter
 * @property \Illuminate\Support\Carbon|null $mulai_navigasi
 * @property \Illuminate\Support\Carbon|null $waktu_tiba
 * @property \Illuminate\Support\Carbon $dikunjungi_pada
 * @property-read \App\Models\Lokasi $lokasi
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKunjungan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKunjungan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKunjungan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKunjungan whereDikunjungiPada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKunjungan whereIdLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKunjungan whereIdRiwayat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKunjungan whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKunjungan whereJarakTempuh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKunjungan whereLatitudeArrived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKunjungan whereLongitudeArrived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKunjungan whereMulaiNavigasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKunjungan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKunjungan whereWaktuTiba($value)
 */
	class RiwayatKunjungan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string|null $no_hp
 * @property string|null $foto
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ApprovalLog> $approvalLogs
 * @property-read int|null $approval_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorit> $favorits
 * @property-read int|null $favorits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lokasi> $lokasi
 * @property-read int|null $lokasi_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lokasi> $lokasiDifavorit
 * @property-read int|null $lokasi_difavorit_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pengaduan> $pengaduans
 * @property-read int|null $pengaduans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RiwayatKunjungan> $riwayatKunjungans
 * @property-read int|null $riwayat_kunjungans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

