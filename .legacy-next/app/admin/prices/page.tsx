import { DataTable, type DataColumn } from "@/components/ui/data-table";
import { FilterPanel } from "@/components/ui/filter-panel";
import { InputField, SelectField } from "@/components/ui/form-field";
import { PanelCard } from "@/components/ui/panel-card";
import { SectionHeader } from "@/components/ui/section-header";
import { Button } from "@/components/ui/button";
import { formatCurrency, formatDate } from "@/lib/utils";
import { taniSyncRepository } from "@/lib/api/services";
import type { CommodityPrice } from "@/lib/types";

export default async function AdminPricesPage() {
  const prices = await taniSyncRepository.getCommodityPrices();

  const columns: Array<DataColumn<CommodityPrice>> = [
    {
      key: "commodity",
      header: "Komoditas",
      render: (row) => (
        <div>
          <p className="font-heading text-base font-bold text-ink">{row.commodityName}</p>
          <p className="text-xs text-muted">{row.sourceNote}</p>
        </div>
      )
    },
    {
      key: "price",
      header: "Harga",
      render: (row) => <span className="font-heading text-lg font-bold text-ink">{formatCurrency(row.price)}</span>
    },
    {
      key: "effectiveDate",
      header: "Berlaku",
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
          {row.trend === "steady" ? "stabil" : `${row.trendPercent}% ${row.trend === "up" ? "naik" : "turun"}`}
        </span>
      )
    }
  ];

  return (
    <div className="space-y-8">
      <SectionHeader
        eyebrow="Harga harian"
        title="Update harga komoditas"
        description="Harga pada MVP dikelola secara manual oleh admin agar tetap stabil walau belum ada integrasi pihak ketiga."
        actions={<Button>Input harga baru</Button>}
      />

      <FilterPanel
        items={[
          { label: "Tanggal berlaku", content: <InputField label="" type="date" className="py-3" /> },
          {
            label: "Komoditas",
            content: (
              <SelectField label="" defaultValue="semua" className="py-3">
                <option value="semua">Semua komoditas</option>
                {prices.map((price) => (
                  <option key={price.id} value={price.commodityId}>
                    {price.commodityName}
                  </option>
                ))}
              </SelectField>
            )
          },
          {
            label: "Sumber",
            content: (
              <SelectField label="" defaultValue="manual" className="py-3">
                <option value="manual">Input manual admin</option>
                <option value="pasar">Rekap pasar mitra</option>
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

      <PanelCard>
        <DataTable columns={columns} rows={prices} />
      </PanelCard>
    </div>
  );
}
