import { ExportCard } from "@/components/ui/export-card";
import { FilterPanel } from "@/components/ui/filter-panel";
import { InputField, SelectField } from "@/components/ui/form-field";
import { PanelCard } from "@/components/ui/panel-card";
import { SectionHeader } from "@/components/ui/section-header";
import { Button } from "@/components/ui/button";
import { AppImage } from "@/components/ui/app-image";
import { appAssets } from "@/lib/assets";

export default function AdminReportsPage() {
  return (
    <div className="space-y-8">
      <SectionHeader
        eyebrow="Laporan"
        title="Analitik dan ekspor"
        description="Frontend ini menyiapkan alur laporan, filter, dan pilihan ekspor. Proses file aktual akan dihubungkan ke backend Laravel."
      />

      <div className="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
        <PanelCard className="space-y-6">
          <FilterPanel
            items={[
              { label: "Rentang tanggal", content: <InputField label="" type="text" placeholder="01 Apr 2026 - 10 Apr 2026" className="py-3" /> },
              {
                label: "Komoditas",
                content: (
                  <SelectField label="" defaultValue="semua" className="py-3">
                    <option value="semua">Semua komoditas</option>
                    <option value="padi">Padi Ciherang</option>
                    <option value="jagung">Jagung Manis</option>
                    <option value="cabai">Cabai Merah</option>
                  </SelectField>
                )
              },
              {
                label: "Petani",
                content: (
                  <SelectField label="" defaultValue="semua" className="py-3">
                    <option value="semua">Semua petani</option>
                    <option value="rahmat">Bapak Rahmat</option>
                    <option value="sari">Ibu Sari</option>
                  </SelectField>
                )
              }
            ]}
            actions={
              <>
                <Button variant="secondary">Reset</Button>
                <Button>Terapkan</Button>
              </>
            }
          />

          <div className="grid gap-4 md:grid-cols-3">
            {[
              ["Total panen", "4.2 ton", "Periode berjalan"],
              ["Catatan diverifikasi", "18", "Siap rekap"],
              ["Catatan menunggu", "3", "Butuh tindak lanjut"]
            ].map(([label, value, detail]) => (
              <div key={label} className="rounded-[1.5rem] bg-surface-soft p-5">
                <p className="text-sm font-semibold text-muted">{label}</p>
                <p className="mt-2 font-heading text-3xl font-extrabold text-ink">{value}</p>
                <p className="mt-2 text-sm text-muted">{detail}</p>
              </div>
            ))}
          </div>

          <div className="space-y-4">
            <ExportCard title="Ekspor PDF" description="Ringkasan formal untuk kebutuhan administrasi desa dan pelaporan rutin." icon="picture_as_pdf" />
            <ExportCard title="Ekspor Excel" description="Format lanjutan untuk pengolahan data lebih detail di luar aplikasi." icon="table_view" />
            <ExportCard title="Ekspor JSON" description="Payload pengembang untuk validasi struktur data dan integrasi API." icon="data_object" />
          </div>
        </PanelCard>

        <PanelCard muted className="relative overflow-hidden p-0">
          <AppImage src={appAssets.reportLandscape} alt="Lanskap pertanian desa" width={1200} height={1200} className="h-full min-h-[420px] w-full" />
          <div className="absolute inset-x-8 bottom-8 rounded-[1.5rem] bg-white/90 p-6 backdrop-blur">
            <p className="text-xs font-bold uppercase tracking-[0.18em] text-accent">Catatan implementasi</p>
            <h3 className="mt-2 font-heading text-2xl font-extrabold text-ink">Template laporan sudah siap.</h3>
            <p className="mt-3 text-sm leading-7 text-muted">Tahap backend berikutnya cukup menghubungkan trigger ekspor ke endpoint Laravel dan generator file.</p>
          </div>
        </PanelCard>
      </div>
    </div>
  );
}
