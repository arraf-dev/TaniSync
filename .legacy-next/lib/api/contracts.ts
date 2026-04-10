import type {
  AdminDashboardData,
  Commodity,
  CommodityPrice,
  FarmerDashboardData,
  HarvestLog,
  ReportExportRequest,
  Role,
  User
} from "@/lib/types";

export const apiContracts = {
  login: { method: "POST", path: "/auth/login" },
  register: { method: "POST", path: "/auth/register" },
  logout: { method: "POST", path: "/auth/logout" },
  commodities: { method: "GET", path: "/commodities" },
  prices: { method: "GET", path: "/commodity-prices" },
  harvests: { method: "GET", path: "/harvest-logs" },
  adminDashboard: { method: "GET", path: "/dashboard/admin" },
  farmerDashboard: { method: "GET", path: "/dashboard/farmer" },
  exportReport: { method: "GET", path: "/reports/harvests/export" }
} as const;

export interface TaniSyncRepository {
  login(role: Role, identifier: string): Promise<User>;
  register(payload: { name: string; email: string; role: Role; village: string }): Promise<User>;
  getCurrentUser(role: Role): Promise<User>;
  getAdminDashboard(): Promise<AdminDashboardData>;
  getFarmerDashboard(): Promise<FarmerDashboardData>;
  getCommodities(): Promise<Commodity[]>;
  getCommodityPrices(): Promise<CommodityPrice[]>;
  getHarvestLogs(role?: Role): Promise<HarvestLog[]>;
  exportReport(request: ReportExportRequest): Promise<{ message: string }>;
}
