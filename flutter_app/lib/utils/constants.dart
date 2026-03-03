import 'package:flutter/material.dart';

class AppColors {
  // Brand Colors
  static const Color background = Color(0xFFFBFBFF);
  static const Color accentBlue = Color(0xFF3581B8);
  static const Color primaryNavy = Color(0xFF111D4A);
  
  // Status Colors
  static const Color success = Color(0xFF10B981);
  static const Color warning = Color(0xFFF59E0B);
  static const Color error = Color(0xFFEF4444);
  static const Color info = Color(0xFF3B82F6);
  
  // Text Colors
  static const Color textPrimary = Color(0xFF111D4A);
  static const Color textSecondary = Color(0xFF6B7280);
  static const Color textMuted = Color(0xFF9CA3AF);
  
  // Border Colors
  static const Color border = Color(0xFFE5E7EB);
}

class AppSizes {
  static const double paddingXS = 4.0;
  static const double paddingS = 8.0;
  static const double paddingM = 16.0;
  static const double paddingL = 24.0;
  static const double paddingXL = 32.0;
  
  static const double radiusS = 8.0;
  static const double radiusM = 12.0;
  static const double radiusL = 16.0;
  static const double radiusXL = 24.0;
}

class AppStrings {
  static const String appName = 'LIKIZOPRO';
  static const String tagline = 'Enterprise Leave Management';
  
  // API Base URL - Update for production
  static const String baseUrl = 'http://localhost:8080/api';
  
  // Error Messages
  static const String networkError = 'Please check your internet connection';
  static const String serverError = 'Something went wrong. Please try again';
  static const String unauthorized = 'Session expired. Please login again';
}
