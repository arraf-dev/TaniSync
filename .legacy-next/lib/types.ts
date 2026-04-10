import type { ReactNode } from "react";

export type Role = "admin" | "petani";
export type HarvestStatus = "terverifikasi" | "menunggu" | "butuh-review";

export interface User {
  id: string;
  name: string;
  email: string;
  role: Role;
  village: string;
  avatar: string;
}

export interface Commodity {
  id: string;
  name: string;
  category: string;
  unit: string;
  status: "aktif" | "nonaktif";
  description: string;
}

export interface CommodityPrice {
  id: string;
  commodityId: string;
  commodityName: string;
  category: string;
  price: number;
  effectiveDate: string;
  sourceNote: string;
  trend: "up" | "down" | "steady";
  trendPercent: number;
}

export interface HarvestLog {
  id: string;
  userId: string;
  userName: string;
  commodityId: string;
  commodityName: string;
  harvestDate: string;
  quantity: number;
  unit: string;
  note: string;
  location: string;
  quality: string;
  status: HarvestStatus;
}

export interface DashboardMetric {
  label: string;
  value: string;
  detail: string;
  icon: string;
  tone?: "primary" | "accent" | "success" | "warning";
}

export interface TrendPoint {
  label: string;
  value: number;
}

export interface QuickAction {
  title: string;
  description: string;
  href: string;
  icon: string;
  tone?: "primary" | "accent" | "success" | "neutral";
}

export interface AdminDashboardData {
  metrics: DashboardMetric[];
  trends: TrendPoint[];
  harvestDistribution: TrendPoint[];
  quickActions: QuickAction[];
}

export interface FarmerDashboardData {
  metrics: DashboardMetric[];
  trends: TrendPoint[];
  quickActions: QuickAction[];
  latestPrices: CommodityPrice[];
}

export interface ReportExportRequest {
  format: "pdf" | "excel" | "json";
  period: string;
  commodityId?: string;
  userId?: string;
}

export interface NavigationItem {
  label: string;
  href: string;
  icon: string;
}

export interface FilterItem {
  label: string;
  content: ReactNode;
}
