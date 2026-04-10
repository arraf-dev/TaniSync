import { DataTable, type DataColumn } from "@/components/ui/data-table";
import { PanelCard } from "@/components/ui/panel-card";
import { SectionHeader } from "@/components/ui/section-header";
import { formatCurrency, formatDate } from "@/lib/utils";
import { taniSyncRepository } from "@/lib/api/services";
import type { CommodityPrice } from "@/lib/types";

export default async function FarmerPricesPage() {
  const prices = await taniSyncRepository.getCommodityPrices();

  const columns: Array<DataColumn<CommodityPrice>> = [
    {
      key: "commodity",
      header: "Komoditas",
      render: (row) => (
        <div>
          <p className="font-heading text-base font-bold text-ink">{row.commodityName}</p>
          <p className="text-xs text-muted">{row.category}</p>
        </div>
      )
    },
    {
      key: "price",
      header: "Harga",
      render: (row) => <span className="font-heading text-lg font-bold text-ink">{formatCurrency(row.price)}</span>
    },
    {
      key: "date",
      header: "Diperbarui",
      render: (row) => <span className="font-semibold text-ink">{formatDate(row.effectiveDate)}</span>
    },
    {
      key: "trend",
      header: "Trend",
      render: (row) => (
        <span
          className={`rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] ${
            row.trend === "up"
              ? "bg-primary-soft text-primary"
              : row.trend === "down"
                ? "bg-red-50 text-danger"
                : "bg-white text-muted"
          }`}
        >
          {row.trend === "steady" ? "stabil" : `${row.trendPercent}%`}
        </span>
      )
    }
  ];

  return (
    <div className="space-y-8">
      <SectionHeader
        eyebrow="Harga komoditas"
        title="Pantau referensi harga terbaru"
        description="Semua harga di tahap frontend ini memakai sumber mock, tetapi struktur tabelnya sudah mengikuti data yang akan datang dari Laravel."
      />

      <PanelCard>
        <DataTable columns={columns} rows={prices} />
      </PanelCard>
    </div>
  );
}
