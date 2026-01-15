package com.zabalagailetak.hrapp.data.api

import com.zabalagailetak.hrapp.domain.model.*
import retrofit2.Response
import retrofit2.http.*

/**
 * Vacation API service
 */
interface VacationApiService {

    @GET("vacations/balance")
    suspend fun getBalance(): Response<VacationBalance>

    @GET("vacations/balance/{employeeId}")
    suspend fun getBalanceForEmployee(
        @Path("employeeId") employeeId: Int
    ): Response<VacationBalance>

    @POST("vacations/requests")
    suspend fun createRequest(
        @Body request: CreateVacationRequest
    ): Response<VacationRequest>

    @GET("vacations/requests")
    suspend fun getMyRequests(): Response<VacationRequestsResponse>

    @GET("vacations/requests/{id}")
    suspend fun getRequestById(
        @Path("id") id: Int
    ): Response<VacationRequest>

    @GET("vacations/pending/manager")
    suspend fun getPendingManagerRequests(): Response<VacationRequestsResponse>

    @GET("vacations/pending/hr")
    suspend fun getPendingHRRequests(): Response<VacationRequestsResponse>

    @POST("vacations/requests/{id}/approve-manager")
    suspend fun approveByManager(
        @Path("id") id: Int,
        @Body notes: Map<String, String?>
    ): Response<VacationRequest>

    @POST("vacations/requests/{id}/approve-hr")
    suspend fun approveByHR(
        @Path("id") id: Int,
        @Body notes: Map<String, String?>
    ): Response<VacationRequest>

    @POST("vacations/requests/{id}/reject")
    suspend fun rejectRequest(
        @Path("id") id: Int,
        @Body reason: Map<String, String>
    ): Response<VacationRequest>

    @POST("vacations/requests/{id}/cancel")
    suspend fun cancelRequest(
        @Path("id") id: Int
    ): Response<VacationRequest>
}
