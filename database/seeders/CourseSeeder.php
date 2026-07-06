<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Field;
use App\Models\SubTopic;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // TYT Müfredatı
        $this->seedTYT();
        
        // AYT Müfredatı
        $this->seedAYT();
        
        // DGS Müfredatı
        $this->seedDGS();
        
        // KPSS Müfredatı
        $this->seedKPSS();
    }

    private function seedTYT()
    {
        $tyt = Field::where('slug', 'tyt')->first();
        
        $courses = [
            'Matematik' => [
                'Temel Kavramlar' => [
                    'Sayı Kümeleri',
                    'Bölme ve Bölünebilme',
                    'EBOB - EKOK',
                    'Rasyonel Sayılar',
                    'Basit Eşitsizlikler',
                ],
                'Üslü Sayılar' => [
                    'Üslü Sayıların Özellikleri',
                    'Kök Kavramı',
                    'Köklü Sayılar',
                ],
                'Çarpanlara Ayırma' => [
                    'Çarpanlara Ayırma Yöntemleri',
                    'Özdeşlikler',
                    'İkinci Dereceden Denklemler',
                ],
                'Oran Orantı' => [
                    'Oran',
                    'Orantı',
                    'Doğru ve Ters Orantı',
                ],
                'Denklem ve Eşitsizlikler' => [
                    'Birinci Dereceden Denklemler',
                    'Mutlak Değer',
                    'Eşitsizlikler',
                ],
                'Problemler' => [
                    'Yaş Problemleri',
                    'İşçi Problemleri',
                    'Yüzde Problemleri',
                    'Karışım Problemleri',
                ],
                'Kümeler' => [
                    'Küme Kavramı',
                    'Küme İşlemleri',
                    'Kartezyen Çarpım',
                ],
                'Fonksiyonlar' => [
                    'Fonksiyon Kavramı',
                    'Fonksiyon Çeşitleri',
                    'Fonksiyonlarda İşlemler',
                ],
                'Polinomlar' => [
                    'Polinomların Çarpımı',
                    'Polinomların Bölümü',
                    'Çarpanlara Ayırma',
                ],
                'Permütasyon ve Kombinasyon' => [
                    'Permütasyon',
                    'Kombinasyon',
                    'Olasılık',
                ],
            ],
            'Türkçe' => [
                'Ses Bilgisi' => [
                    'Ünlüler',
                    'Ünsüzler',
                    'Ses Olayları',
                    'Hece',
                ],
                'Kelime Bilgisi' => [
                    'Kelimenin Yapısı',
                    'Kök ve Ek',
                    'Yapım ve Çekim Ekleri',
                    'Birleşik Kelimeler',
                ],
                'Sözcükte Anlam' => [
                    'Gerçek ve Mecaz Anlam',
                    'Anlam İlişkileri',
                    'Deyimler',
                    'Atasözleri',
                ],
                'Cümle Bilgisi' => [
                    'Cümlenin Öğeleri',
                    'Basit ve Birleşik Cümle',
                    'Fiilimsiler',
                ],
                'Anlatım Bozuklukları' => [
                    'Cümlede Anlam',
                    'Anlatım Bozukluğu Çeşitleri',
                ],
                'Yazım Kuralları' => [
                    'Büyük Harflerin Kullanımı',
                    'Noktalama İşaretleri',
                    'Yazımı Karıştırılan Kelimeler',
                ],
                'Paragraf' => [
                    'Ana Düşünce',
                    'Paragrafın Yapısı',
                    'Paragraf Türleri',
                ],
            ],
            'Fizik' => [
                'Fizik Bilimine Giriş' => [
                    'Fiziksel Nicelikler',
                    'Ölçme ve Belirsizlik',
                    'Vektörler',
                ],
                'Madde ve Özellikleri' => [
                    'Maddenin Halleri',
                    'Özkütle',
                    'Basınç',
                ],
                'Hareket' => [
                    'Hız ve Sürat',
                    'İvme',
                    'Serbest Düşme',
                    'Düşey Atış',
                ],
                'Kuvvet ve Hareket' => [
                    'Newton Yasaları',
                    'Sürtünme Kuvveti',
                    'İş ve Enerji',
                ],
                'Elektrostatik' => [
                    'Elektrik Yükü',
                    'Coulomb Kanunu',
                    'Elektrik Alanı',
                ],
                'Optik' => [
                    'Işığın Kırılması',
                    'Mercekler',
                    'Işığın Yansıması',
                ],
            ],
            'Kimya' => [
                'Kimya Bilimi' => [
                    'Kimyanın Konusu',
                    'Dalları',
                    'Laboratuvar Güvenliği',
                ],
                'Atom ve Periyodik Sistem' => [
                    'Atom Modelleri',
                    'Modern Atom Teorisi',
                    'Periyodik Sistem',
                    'Periyodik Özellikler',
                ],
                'Kimyasal Türler Arası Etkileşimler' => [
                    'İyonik Bağ',
                    'Kovalent Bağ',
                    'Metalik Bağ',
                ],
                'Maddenin Halleri' => [
                    'Katı Hal',
                    'Sıvı Hal',
                    'Gaz Hal',
                    'Hal Değişimleri',
                ],
                'Kimyasal Tepkimeler' => [
                    'Fiziksel ve Kimyasal Değişim',
                    'Tepkime Türleri',
                    'Denklem Denkleştirme',
                ],
                'Karışımlar' => [
                    'Homojen Karışımlar',
                    'Heterojen Karışımlar',
                    'Ayırma Yöntemleri',
                ],
            ],
            'Biyoloji' => [
                'Yaşam Bilimi Biyoloji' => [
                    'Biyolojinin Konusu',
                    'Canlıların Özellikleri',
                    'Canlıların Sınıflandırılması',
                ],
                'Hücre' => [
                    'Hücre Teorisi',
                    'Hücre Organelleri',
                    'Prokaryot ve Ökaryot Hücre',
                ],
                'Hücre Bölünmeleri' => [
                    'Mitoz Bölünme',
                    'Mayoz Bölünme',
                    'Üreme',
                ],
                'Kalıtım' => [
                    'DNA ve RNA',
                    'Protein Sentezi',
                    'Mendel Genetiği',
                ],
                'Ekosistem' => [
                    'Besin Zincirleri',
                    'Enerji Akışı',
                    'Madde Döngüleri',
                ],
            ],
            'Tarih' => [
                'Tarih Bilimi' => [
                    'Tarih ve Zaman',
                    'Tarihi Kaynaklar',
                    'İlk Çağ Medeniyetleri',
                ],
                'İslam Tarihi' => [
                    'İslam Öncesi Türkler',
                    'İslamın Doğuşu',
                    'İlk İslam Devletleri',
                ],
                'Türk İslam Devletleri' => [
                    'Büyük Selçuklu Devleti',
                    'Anadolu Selçukluları',
                    'Beylikler Dönemi',
                ],
                'Osmanlı Devleti Kuruluş' => [
                    'Osmanlı Kuruluşu',
                    'Yükselme Dönemi',
                    'Duraklama Dönemi',
                ],
            ],
            'Coğrafya' => [
                'Doğa ve İnsan' => [
                    'Konum',
                    'Harita Bilgisi',
                    'Koordinat Sistemi',
                ],
                'Dünya ve Türkiye' => [
                    'Türkiye\'nin Konumu',
                    'Türkiye\'nin Coğrafi Özellikleri',
                    'İklim',
                ],
                'Nüfus ve Yerleşme' => [
                    'Nüfus Özellikleri',
                    'Göçler',
                    'Şehirleşme',
                ],
                'Ekonomi' => [
                    'Tarım',
                    'Hayvancılık',
                    'Sanayi',
                ],
            ],
            'Felsefe' => [
                'Felsefe Nedir' => [
                    'Felsefenin Tanımı',
                    'Felsefenin Konusu',
                    'Felsefe Dalları',
                ],
                'Bilgi Felsefesi' => [
                    'Bilgi',
                    'Bilgi Türleri',
                    'Doğruluk ve Hakikat',
                ],
                'Mantık' => [
                    'Mantık Nedir',
                    'Kavram',
                    'Önerme',
                    'Çıkarım',
                ],
                'Ahlak Felsefesi' => [
                    'Ahlak',
                    'Değer',
                    'Özgürlük',
                ],
            ],
            'Din Kültürü ve Ahlak Bilgisi' => [
                'İnanç' => [
                    'Din',
                    'İman',
                    'İbadet',
                ],
                'İslam Dini' => [
                    'İslam\'ın Şartları',
                    'İmanın Şartları',
                    'Namaz',
                    'Oruç',
                ],
                'Ahlak' => [
                    'Güzel Ahlak',
                    'Kötü Ahlak',
                    'Toplum ve Ahlak',
                ],
            ],
        ];

        $this->createCoursesWithTopics($tyt, $courses, 1);
    }

    private function seedAYT()
    {
        $ayt = Field::where('slug', 'ayt')->first();
        
        $courses = [
            'Matematik' => [
                'Fonksiyonlar' => [
                    'Fonksiyonlarda İşlemler',
                    'Bileşke Fonksiyon',
                    'Ters Fonksiyon',
                ],
                'Polinomlar' => [
                    'Polinomlarda Bölme',
                    'Çarpanlara Ayırma',
                    'Polinomlarla İşlemler',
                ],
                'Logaritma' => [
                    'Logaritma Tanımı',
                    'Logaritma Özellikleri',
                    'Logaritma Denklemleri',
                ],
                'Trigonometri' => [
                    'Trigonometrik Fonksiyonlar',
                    'Trigonometrik Denklemler',
                    'Trigonometrik İfadeler',
                ],
                'Diziler' => [
                    'Aritmetik Dizi',
                    'Geometrik Dizi',
                    'Karmaşık Sayılar',
                ],
                'Limit ve Süreklilik' => [
                    'Limit Kavramı',
                    'Limit Özellikleri',
                    'Süreklilik',
                ],
                'Türev' => [
                    'Türev Tanımı',
                    'Türev Kuralları',
                    'Türev Uygulamaları',
                ],
                'İntegral' => [
                    'Belirsiz İntegral',
                    'Belirli İntegral',
                    'İntegral Uygulamaları',
                ],
            ],
            'Fizik' => [
                'Elektrik ve Manyetizma' => [
                    'Elektrik Akımı',
                    'Manyetik Alan',
                    'Elektromanyetik İndüksiyon',
                ],
                'Modern Fizik' => [
                    'Atom Fiziği',
                    'Kuantum Fiziği',
                    'Radyoaktivite',
                ],
                'Dalgalar ve Optik' => [
                    'Mekanik Dalgalar',
                    'Elektromanyetik Dalgalar',
                    'Kırılma ve Yansıma',
                ],
                'Çembersel Hareket' => [
                    'Düzgün Çembersel Hareket',
                    'Açısal Hız',
                    'Merkezkaç Kuvvet',
                ],
            ],
            'Kimya' => [
                'Modern Atom Teorisi' => [
                    'Atom Modelleri',
                    'Elektronların Dizilişi',
                    'Kuantum Sayıları',
                ],
                'Gazlar' => [
                    'Gaz Yasaları',
                    'İdeal Gaz Denklemi',
                    'Kısmi Basınç',
                ],
                'Kimyasal Tepkimeler ve Enerji' => [
                    'Reaksiyon Hızı',
                    'Kimyasal Denge',
                    'Entalpi',
                ],
                'Asit Baz Dengesi' => [
                    'Asit ve Baz',
                    'pH Kavramı',
                    'Tampon Çözeltiler',
                ],
                'Çözünürlük Dengesi' => [
                    'Çözünürlük',
                    'Çözünürlük Çarpımı',
                    'Çökelme Tepkimeleri',
                ],
            ],
            'Biyoloji' => [
                'Hücre Biyolojisi' => [
                    'Hücre Zarı',
                    'Madde Alış Verişi',
                    'Hücre Organelleri',
                ],
                'Sinir Sistemi' => [
                    'Sinir Hücresi',
                    'Sinir İmpulsları',
                    'Merkezi Sinir Sistemi',
                ],
                'Endokrin Sistem' => [
                    'Hormonlar',
                    'Bezler',
                    'Hormonal Düzenlemeler',
                ],
                'Dolaşım Sistemi' => [
                    'Kan',
                    'Kalp',
                    'Kan Dolaşımı',
                ],
                'Solunum Sistemi' => [
                    'Solunum Organları',
                    'Gaz Değişimi',
                    'Hücresel Solunum',
                ],
            ],
            'Türk Dili ve Edebiyatı' => [
                'Edebiyat Tarihi' => [
                    'Divan Edebiyatı',
                    'Tanzimat Edebiyatı',
                    'Cumhuriyet Edebiyatı',
                ],
                'Edebi Sanatlar' => [
                    'Söz Sanatları',
                    'Anlam Sanatları',
                    'Yapı Sanatları',
                ],
                'Metin Bilgisi' => [
                    'Şiir',
                    'Hikaye',
                    'Roman',
                    'Deneme',
                ],
            ],
            'Tarih' => [
                'Osmanlı Devleti' => [
                    'Duraklama Dönemi',
                    'Gerileme Dönemi',
                    'Dağılma Dönemi',
                ],
                'Osmanlıda Yenilik Hareketleri' => [
                    'Islahat Hareketleri',
                    'Meşrutiyet Dönemi',
                    'İttihat ve Terakki',
                ],
                'Birinci Dünya Savaşı' => [
                    'Savaşın Nedenleri',
                    'Osmanlının Savaşa Girişi',
                    'Cepheler',
                ],
                'Kurtuluş Savaşı' => [
                    'Kuvayi Milliye',
                    'TBMM\'nin Açılması',
                    'Cepheler',
                ],
            ],
            'Coğrafya' => [
                'Türkiye Coğrafyası' => [
                    'Türkiye\'nin Jeolojik Yapısı',
                    'İklim Özellikleri',
                    'Doğal Bitki Örtüsü',
                ],
                'Beşeri Coğrafya' => [
                    'Nüfus',
                    'Yerleşme',
                    'Ekonomik Faaliyetler',
                ],
                'Bölgeler Coğrafyası' => [
                    'Türkiye\'nin Bölgeleri',
                    'Bölgesel Özellikler',
                ],
            ],
        ];

        $this->createCoursesWithTopics($ayt, $courses, 100);
    }

    private function seedDGS()
    {
        $dgs = Field::where('slug', 'dgs')->first();
        
        $courses = [
            'Sözel' => [
                'Türkçe' => [
                    'Anlam Bilgisi',
                    'Sözcük Türleri',
                    'Cümle Bilgisi',
                    'Paragraf',
                ],
                'Tarih' => [
                    'Osmanlı Tarihi',
                    'Türkiye Cumhuriyeti Tarihi',
                    'İnkılap Tarihi',
                ],
                'Coğrafya' => [
                    'Türkiye Coğrafyası',
                    'Beşeri Coğrafya',
                    'Ekonomik Coğrafya',
                ],
            ],
            'Sayısal' => [
                'Matematik' => [
                    'Temel Matematik',
                    'Denklemler',
                    'Fonksiyonlar',
                    'Geometri',
                ],
            ],
        ];

        $this->createCoursesWithTopics($dgs, $courses, 200);
    }

    private function seedKPSS()
    {
        $kpss = Field::where('slug', 'kpss')->first();
        
        $courses = [
            'Genel Yetenek' => [
                'Türkçe' => [
                    'Sözcük Bilgisi',
                    'Cümle Bilgisi',
                    'Paragraf',
                    'Anlam Bilgisi',
                ],
                'Matematik' => [
                    'Temel Matematik',
                    'Problemler',
                    'Geometri',
                ],
            ],
            'Genel Kültür' => [
                'Tarih' => [
                    'Türk Tarihi',
                    'Osmanlı Tarihi',
                    'Cumhuriyet Tarihi',
                    'İnkılap Tarihi',
                ],
                'Coğrafya' => [
                    'Türkiye Coğrafyası',
                    'Fiziki Coğrafya',
                    'Beşeri Coğrafya',
                ],
                'Vatandaşlık' => [
                    'Anayasa',
                    'İnsan Hakları',
                    'Demokrasi',
                ],
            ],
        ];

        $this->createCoursesWithTopics($kpss, $courses, 300);
    }

    private function createCoursesWithTopics($field, $courses, $startOrder)
    {
        $courseOrder = $startOrder;
        
        foreach ($courses as $courseName => $topics) {
            $course = Course::create([
                'field_id' => $field->id,
                'name' => $courseName,
                'order' => $courseOrder++,
                'is_active' => true,
            ]);

            $topicOrder = 1;
            foreach ($topics as $topicName => $subTopics) {
                $topic = Topic::create([
                    'course_id' => $course->id,
                    'name' => $topicName,
                    'order' => $topicOrder++,
                    'is_active' => true,
                ]);

                $subTopicOrder = 1;
                foreach ($subTopics as $subTopicName) {
                    SubTopic::create([
                        'topic_id' => $topic->id,
                        'name' => $subTopicName,
                        'order' => $subTopicOrder++,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
