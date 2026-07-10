<?php

{{-- ============================================================
     فایل: resources/views/shop/checkout/success.blade.php
     توضیح: صفحه موفقیت سفارش
     ============================================================ --}}
@extends('layout.app')
@section('title', 'سفارش ثبت شد')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 text-center">

                {{-- انیمیشن تیک --}}
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                         style="width:100px;height:100px;background:#f0fdf4;animation:scaleIn .4s ease">
                        <i class="fas fa-check-circle text-success" style="font-size:3.5rem"></i>
                    </div>
                </div>

                <h2 class="fw-800 mb-2">سفارش شما ثبت شد! 🎉</h2>
                <p class="text-muted mb-4">
                    سفارش شما با موفقیت ثبت شد و در اسرع وقت پردازش خواهد شد.
                    <br>پیامک تأیید به موبایل شما ارسال می‌شود.
                </p>

                <div class="card border-0 shadow-sm mb-4 text-start" style="border-radius:14px">
                    <div class="card-body p-4">
                        <div class="row g-3 text-center">
                            <div class="col-4">
                                <div class="fw-700 text-success fs-5">📦</div>
                                <div style="font-size:.78rem;color:#64748b">ثبت شد</div>
                            </div>
                            <div class="col-4">
                                <div class="fw-700 text-muted fs-5">🏭</div>
                                <div style="font-size:.78rem;color:#64748b">آماده‌سازی</div>
                            </div>
                            <div class="col-4">
                                <div class="fw-700 text-muted fs-5">🚚</div>
                                <div style="font-size:.78rem;color:#64748b">ارسال</div>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height:6px;border-radius:3px">
                            <div class="progress-bar bg-success" style="width:20%"></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('shop.home') }}" class="btn btn-success btn-lg px-4">
                        <i class="fas fa-home me-2"></i>صفحه اصلی
                    </a>
                    <a href="{{ route('shop.orders') }}" class="btn btn-outline-success btn-lg px-4">
                        <i class="fas fa-box me-2"></i>سفارش‌های من
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes scaleIn {
            from { transform: scale(0); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }
    </style>
@endsection

