import {
  adminDashboard,
  adminProfile,
  commodities,
  commodityPrices,
  farmerDashboard,
  farmerProfile,
  harvestLogs
} from "@/lib/mock-data";
import type { TaniSyncRepository } from "@/lib/api/contracts";
import type { ReportExportRequest, Role, User } from "@/lib/types";

const wait = (ms = 120) => new Promise((resolve) => setTimeout(resolve, ms));

export class MockTaniSyncRepository implements TaniSyncRepository {
  async login(role: Role, _identifier: string): Promise<User> {
    await wait();
    return role === "admin" ? adminProfile : farmerProfile;
  }

  async register(payload: { name: string; email: string; role: Role; village: string }): Promise<User> {
    await wait();
    return {
      id: `mock-${payload.role}`,
      name: payload.name,
      email: payload.email,
      role: payload.role,
      village: payload.village,
      avatar: payload.role === "admin" ? adminProfile.avatar : farmerProfile.avatar
    };
  }

  async getCurrentUser(role: Role) {
    await wait();
    return role === "admin" ? adminProfile : farmerProfile;
  }

  async getAdminDashboard() {
    await wait();
    return adminDashboard;
  }

  async getFarmerDashboard() {
    await wait();
    return farmerDashboard;
  }

  async getCommodities() {
    await wait();
    return commodities;
  }

  async getCommodityPrices() {
    await wait();
    return commodityPrices;
  }

  async getHarvestLogs(role?: Role) {
    await wait();
    if (role === "petani") return harvestLogs.filter((item) => item.userId === farmerProfile.id);
    return harvestLogs;
  }

  async exportReport(request: ReportExportRequest) {
    await wait();
    return { message: `Laporan ${request.format.toUpperCase()} untuk periode ${request.period} siap diproses backend Laravel.` };
  }
}
