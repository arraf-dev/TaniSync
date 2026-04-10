import type { NavigationItem } from "@/lib/types";

export const adminNavigation: NavigationItem[] = [
  { label: "Beranda", href: "/admin/dashboard", icon: "home" },
  { label: "Komoditas", href: "/admin/commodities", icon: "compost" },
  { label: "Harga", href: "/admin/prices", icon: "payments" },
  { label: "Panen", href: "/admin/harvests", icon: "rebase_edit" },
  { label: "Laporan", href: "/admin/reports", icon: "analytics" }
];

export const farmerNavigation: NavigationItem[] = [
  { label: "Beranda", href: "/petani/dashboard", icon: "home" },
  { label: "Catat", href: "/petani/harvests/new", icon: "add_circle" },
  { label: "Riwayat", href: "/petani/harvests", icon: "history" },
  { label: "Harga", href: "/petani/prices", icon: "payments" }
];
