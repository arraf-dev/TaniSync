"use client";

import { useState, type FormEvent } from "react";
import { Button } from "@/components/ui/button";
import { InputField, SelectField, TextareaField } from "@/components/ui/form-field";
import { Icon } from "@/components/ui/icon";
import type { Commodity } from "@/lib/types";

interface HarvestEntryFormProps {
  commodities: Commodity[];
}

export function HarvestEntryForm({ commodities }: HarvestEntryFormProps) {
  const [successMessage, setSuccessMessage] = useState<string | null>(null);

  function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const formData = new FormData(event.currentTarget);
    const commodityId = String(formData.get("commodityId") ?? "");
    const quantity = Number(formData.get("quantity") ?? 0);

    if (!commodityId || !quantity) {
      setSuccessMessage("Mohon pilih komoditas dan isi jumlah panen terlebih dahulu.");
      return;
    }

    setSuccessMessage("Catatan panen berhasil disimpan ke mock repository frontend dan siap disambungkan ke backend Laravel.");
  }

  return (
    <form onSubmit={handleSubmit} className="surface-panel space-y-6 p-6 md:p-8">
      <div className="grid gap-5 md:grid-cols-2">
        <SelectField label="Komoditas" name="commodityId" defaultValue="">
          <option value="" disabled>
            Pilih komoditas
          </option>
          {commodities.map((commodity) => (
            <option key={commodity.id} value={commodity.id}>
              {commodity.name}
            </option>
          ))}
        </SelectField>
        <InputField label="Tanggal panen" name="harvestDate" type="date" required />
        <InputField label="Lokasi / blok lahan" name="location" placeholder="Contoh: Blok Utara 02" required />
        <InputField label="Kualitas panen" name="quality" placeholder="Contoh: Grade A" required />
        <InputField label="Jumlah panen" name="quantity" type="number" step="0.01" placeholder="0" required />
        <SelectField label="Satuan" name="unit" defaultValue="kg">
          <option value="kg">Kg</option>
          <option value="kuintal">Kuintal</option>
          <option value="ton">Ton</option>
        </SelectField>
      </div>

      <TextareaField
        label="Catatan tambahan"
        name="note"
        rows={5}
        placeholder="Tulis kondisi panen, cuaca, atau catatan mutu bila diperlukan."
      />

      {successMessage ? (
        <div className="rounded-2xl border border-primary/20 bg-primary-soft px-4 py-3 text-sm text-primary-strong">{successMessage}</div>
      ) : null}

      <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <p className="flex items-center gap-2 text-sm text-muted">
          <Icon name="cloud_done" className="text-lg text-primary" />
          Data tersimpan di mock frontend untuk validasi alur sebelum backend tersedia.
        </p>
        <Button type="submit" className="px-8 py-4 text-base">
          Simpan catatan panen
          <Icon name="save" className="text-xl" />
        </Button>
      </div>
    </form>
  );
}
