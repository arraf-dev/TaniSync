"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { useState, type FormEvent } from "react";
import { Button } from "@/components/ui/button";
import { InputField } from "@/components/ui/form-field";
import { Icon } from "@/components/ui/icon";
import { taniSyncRepository } from "@/lib/api/services";
import type { Role } from "@/lib/types";

interface AuthFormProps {
  mode: "login" | "register";
}

export function AuthForm({ mode }: AuthFormProps) {
  const router = useRouter();
  const [role, setRole] = useState<Role>("petani");
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setLoading(true);
    setError(null);

    const formData = new FormData(event.currentTarget);

    const name = String(formData.get("name") ?? "").trim();
    const email = String(formData.get("email") ?? "").trim();
    const identifier = String(formData.get("identifier") ?? "").trim();
    const village = String(formData.get("village") ?? "").trim();
    const password = String(formData.get("password") ?? "").trim();

    if (!password || (mode === "register" && (!name || !email || !village))) {
      setError("Mohon lengkapi semua data penting sebelum melanjutkan.");
      setLoading(false);
      return;
    }

    if (mode === "login" && !identifier) {
      setError("Masukkan email atau username untuk login.");
      setLoading(false);
      return;
    }

    try {
      if (mode === "register") {
        await taniSyncRepository.register({ name, email, village, role });
      } else {
        await taniSyncRepository.login(role, identifier);
      }

      router.push(role === "admin" ? "/admin/dashboard" : "/petani/dashboard");
    } catch (submissionError) {
      setError(submissionError instanceof Error ? submissionError.message : "Terjadi kendala saat memproses formulir.");
    } finally {
      setLoading(false);
    }
  }

  return (
    <form onSubmit={handleSubmit} className="surface-panel w-full max-w-xl space-y-6 p-6 md:p-8">
      <div className="space-y-2">
        <p className="text-xs font-bold uppercase tracking-[0.22em] text-accent">{mode === "login" ? "Masuk aplikasi" : "Daftar akun baru"}</p>
        <h1 className="font-heading text-4xl font-extrabold text-ink">
          {mode === "login" ? "Masuk ke TaniSync" : "Buat akun TaniSync"}
        </h1>
        <p className="text-sm leading-6 text-muted">
          {mode === "login"
            ? "Pilih peran Anda lalu lanjutkan ke area kerja yang sesuai."
            : "Registrasi awal ini menyiapkan akun dummy frontend yang nanti akan disambungkan ke Laravel."}
        </p>
      </div>

      <div className="grid gap-3 sm:grid-cols-2">
        {(["petani", "admin"] as Role[]).map((item) => (
          <label
            key={item}
            className={`cursor-pointer rounded-[1.5rem] border p-4 transition ${
              role === item ? "border-primary bg-primary-soft text-primary" : "border-line bg-surface-soft text-muted"
            }`}
          >
            <input type="radio" name="role" className="sr-only" value={item} checked={role === item} onChange={() => setRole(item)} />
            <div className="flex items-center gap-3">
              <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/80">
                <Icon name={item === "petani" ? "nature_people" : "admin_panel_settings"} className="text-xl" filled={role === item} />
              </div>
              <div>
                <p className="font-heading text-base font-bold capitalize">{item}</p>
                <p className="text-xs">{item === "petani" ? "Fokus pada catat panen & harga" : "Kelola komoditas, harga, dan laporan"}</p>
              </div>
            </div>
          </label>
        ))}
      </div>

      {mode === "register" ? (
        <div className="grid gap-5 md:grid-cols-2">
          <InputField label="Nama lengkap" name="name" placeholder="Contoh: Bapak Rahmat" required />
          <InputField label="Desa / Gapoktan" name="village" placeholder="Desa Sukamaju" required />
          <InputField className="md:col-span-2" label="Alamat email" name="email" type="email" placeholder="nama@tanisync.id" required />
        </div>
      ) : (
        <InputField
          label="Email atau username"
          name="identifier"
          placeholder="nama@tanisync.id"
          icon={<Icon name="alternate_email" className="text-lg" />}
          required
        />
      )}

      <InputField
        label="Kata sandi"
        name="password"
        type="password"
        placeholder="Minimal 8 karakter"
        icon={<Icon name="lock" className="text-lg" />}
        helper={mode === "login" ? "Gunakan kredensial dummy apa pun untuk meninjau alur frontend." : "Validasi nyata akan dipasang saat backend Laravel tersedia."}
        required
      />

      {error ? <div className="rounded-2xl border border-danger/20 bg-red-50 px-4 py-3 text-sm text-danger">{error}</div> : null}

      <Button type="submit" className="w-full py-4 text-base">
        {loading ? "Memproses..." : mode === "login" ? "Masuk ke dashboard" : "Buat akun & lanjutkan"}
        <Icon name="arrow_forward" className="text-xl" />
      </Button>

      <div className="flex items-center justify-between gap-4 text-sm text-muted">
        <Link href="/" className="font-semibold text-primary hover:underline">
          Kembali ke beranda
        </Link>
        <Link href={mode === "login" ? "/register" : "/login"} className="font-semibold text-primary hover:underline">
          {mode === "login" ? "Belum punya akun?" : "Sudah punya akun?"}
        </Link>
      </div>
    </form>
  );
}
