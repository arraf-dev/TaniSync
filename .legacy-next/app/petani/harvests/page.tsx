import { DataTable, type DataColumn } from "@/components/ui/data-table";
import { EmptyState } from "@/components/ui/empty-state";
import { PanelCard } from "@/components/ui/panel-card";
import { SectionHeader } from "@/components/ui/section-header";
import { formatDate } from "@/lib/utils";
import { taniSyncRepository } from "@/lib/api/services";
import type { HarvestLog } from "@/lib/types";

export default async function FarmerHarvestsPage() {
  const harvests = await taniSyncRepository.getHarvestLogs("petani");

  const columns: Array<DataColumn<HarvestLog>> = [
    {
      key: "commodity",
      header: "Komoditas",
      render: (row) => (
        <div>
          <p className="font-heading text-base font-bold text-ink">{row.commodityName}</p>
          <p className="text-xs text-muted">{row.location}</p>
        </div>
      )
    },
    {
      key: "date",
      header: "Tanggal",
      render: (row) => <span className="font-semibold text-ink">{formatDate(row.harvestDate)}</span>
    },
    {
      key: "quantity",
      header: "Jumlah",
      align: "right",
      render: (row) => <span className="font-heading text-lg font-bold text-ink">{`${row.quantity} ${row.unit}`}</span>
    },
    {
      key: "quality",
      header: "Kualitas",
      render: (row) => <span className="rounded-full bg-white px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-muted">{row.quality}</span>
    },
    {
      key: "status",
      header: "Status",
      render: (row) => (
        <span className="rounded-full bg-primary-soft px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-primary">
          {row.status}
        </span>
      )
    }
  ];

  return (
    <div className="space-y-8">
      <SectionHeader
        eyebrow="Riwayat panen"
        title="Catatan panen pribadi"
        description="Gunakan riwayat ini untuk meninjau hasil produksi, memeriksa kualitas, dan menyiapkan rekap panen berikutnya."
      />

      {harvests.length ? (
        <PanelCard>
          <DataTable columns={columns} rows={harvests} />
        </PanelCard>
      ) : (
        <EmptyState title="Belum ada catatan panen" description="Mulai dari satu catatan sederhana agar riwayat panen Anda tersusun sejak awal." actionLabel="Catat panen" actionHref="/petani/harvests/new" />
      )}
    </div>
  );
}
