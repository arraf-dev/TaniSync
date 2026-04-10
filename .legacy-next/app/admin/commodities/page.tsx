import { DataTable, type DataColumn } from "@/components/ui/data-table";
import { EmptyState } from "@/components/ui/empty-state";
import { PanelCard } from "@/components/ui/panel-card";
import { SectionHeader } from "@/components/ui/section-header";
import { Button } from "@/components/ui/button";
import { taniSyncRepository } from "@/lib/api/services";
import type { Commodity } from "@/lib/types";

export default async function AdminCommoditiesPage() {
  const commodities = await taniSyncRepository.getCommodities();

  const columns: Array<DataColumn<Commodity>> = [
    {
      key: "name",
      header: "Komoditas",
      render: (row) => (
        <div>
          <p className="font-heading text-base font-bold text-ink">{row.name}</p>
          <p className="text-xs text-muted">{row.description}</p>
        </div>
      )
    },
    {
      key: "category",
      header: "Kategori",
      render: (row) => <span className="rounded-full bg-white px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-muted">{row.category}</span>
    },
    {
      key: "unit",
      header: "Satuan",
      align: "center",
      render: (row) => <span className="font-semibold text-ink">{row.unit}</span>
    },
    {
      key: "status",
      header: "Status",
      align: "center",
      render: (row) => (
        <span className={`rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] ${row.status === "aktif" ? "bg-primary-soft text-primary" : "bg-orange-100 text-accent"}`}>
          {row.status}
        </span>
      )
    }
  ];

  return (
    <div className="space-y-8">
      <SectionHeader
        eyebrow="Master data"
        title="Manajemen komoditas"
        description="Kelola daftar komoditas desa agar form panen dan harga harian tetap akurat."
        actions={<Button>Tambah komoditas</Button>}
      />

      {commodities.length ? (
        <PanelCard>
          <DataTable columns={columns} rows={commodities} />
        </PanelCard>
      ) : (
        <EmptyState title="Belum ada komoditas" description="Tambahkan komoditas pertama untuk mulai mencatat harga dan panen." actionLabel="Tambah sekarang" actionHref="/admin/commodities" />
      )}
    </div>
  );
}
