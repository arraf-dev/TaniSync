import { DataTable, type DataColumn } from "@/components/ui/data-table";
import { FilterPanel } from "@/components/ui/filter-panel";
import { InputField, SelectField } from "@/components/ui/form-field";
import { PanelCard } from "@/components/ui/panel-card";
import { SectionHeader } from "@/components/ui/section-header";
import { formatDate } from "@/lib/utils";
import { taniSyncRepository } from "@/lib/api/services";
import type { HarvestLog } from "@/lib/types";

export default async function AdminHarvestsPage() {
  const harvests = await taniSyncRepository.getHarvestLogs();

  const columns: Array<DataColumn<HarvestLog>> = [
    {
      key: "user",
      header: "Petani",
      render: (row) => (
        <div>
          <p className="font-heading text-base font-bold text-ink">{row.userName}</p>
          <p className="text-xs text-muted">{row.location}</p>
        </div>
      )
    },
    {
      key: "commodity",
      header: "Komoditas",
      render: (row) => (
        <div>
          <p className="font-semibold text-ink">{row.commodityName}</p>
          <p className="text-xs text-muted">{row.quality}</p>
        </div>
      )
    },
    {
      key: "quantity",
      header: "Jumlah",
      align: "right",
      render: (row) => <span className="font-heading text-lg font-bold text-ink">{`${row.quantity} ${row.unit}`}</span>
    },
    {
      key: "date",
      header: "Tanggal",
      render: (row) => <span className="font-semibold text-ink">{formatDate(row.harvestDate)}</span>
    },
    {
      key: "status",
      header: "Status",
      render: (row) => (
        <span
          className={`rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] ${
            row.status === "terverifikasi"
              ? "bg-primary-soft text-primary"
              : row.status === "menunggu"
                ? "bg-orange-100 text-accent"
                : "bg-red-50 text-danger"
          }`}
        >
          {row.status}
        </span>
      )
    }
  ];

  return (
    <div className="space-y-8">
      <SectionHeader
        eyebrow="Monitoring panen"
        title="Pantau log panen yang masuk"
        description="Admin dapat meninjau catatan panen terbaru, memeriksa status verifikasi, dan menyiapkan data untuk laporan desa."
      />

      <FilterPanel
        items={[
          { label: "Periode", content: <InputField label="" type="date" className="py-3" /> },
          {
            label: "Komoditas",
            content: (
              <SelectField label="" defaultValue="semua" className="py-3">
                <option value="semua">Semua komoditas</option>
                {harvests.map((harvest) => (
                  <option key={harvest.id} value={harvest.commodityId}>
                    {harvest.commodityName}
                  </option>
                ))}
              </SelectField>
            )
          },
          {
            label: "Status",
            content: (
              <SelectField label="" defaultValue="semua" className="py-3">
                <option value="semua">Semua status</option>
                <option value="terverifikasi">Terverifikasi</option>
                <option value="menunggu">Menunggu</option>
                <option value="butuh-review">Butuh review</option>
              </SelectField>
            )
          }
        ]}
      />

      <PanelCard>
        <DataTable columns={columns} rows={harvests} />
      </PanelCard>
    </div>
  );
}
