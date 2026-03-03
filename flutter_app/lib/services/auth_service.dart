import 'package:flutter/foundation.dart';
import 'package:shared_preferences/shared_preferences.dart';

class AuthService extends ChangeNotifier {
  final SharedPreferences _prefs;
  
  String? _token;
  String? _refreshToken;
  Map<String, dynamic>? _user;
  bool _isLoading = false;
  
  AuthService(this._prefs) {
    _loadFromStorage();
  }
  
  String? get token => _token;
  Map<String, dynamic>? get user => _user;
  bool get isLoading => _isLoading;
  bool get isLoggedIn => _token != null;
  
  int? get userId => _user?['id'];
  String? get userName => _user != null 
      ? '${_user!['first_name']} ${_user!['last_name']}' 
      : null;
  
  void _loadFromStorage() {
    _token = _prefs.getString('token');
    _refreshToken = _prefs.getString('refresh_token');
    
    final userJson = _prefs.getString('user');
    if (userJson != null) {
      // Simple decode - in production use proper JSON
      _user = {'first_name': 'User'}; // Simplified
    }
    notifyListeners();
  }
  
  Future<bool> login(String email, String password) async {
    _isLoading = true;
    notifyListeners();
    
    try {
      // Simulate API call
      await Future.delayed(const Duration(seconds: 1));
      
      // Demo credentials
      if (email == 'admin@likizopro.co.tz' && password == '123456') {
        _token = 'demo_token_${DateTime.now().millisecondsSinceEpoch}';
        _user = {
          'id': 1,
          'email': email,
          'first_name': 'John',
          'last_name': 'Mwaisekwa',
          'role_id': 1,
          'employee_id': 1,
        };
        
        await _prefs.setString('token', _token!);
        await _prefs.setString('user', 'demo_user');
        
        _isLoading = false;
        notifyListeners();
        return true;
      }
      
      _isLoading = false;
      notifyListeners();
      return false;
    } catch (e) {
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }
  
  Future<void> logout() async {
    _token = null;
    _refreshToken = null;
    _user = null;
    
    await _prefs.remove('token');
    await _prefs.remove('refresh_token');
    await _prefs.remove('user');
    
    notifyListeners();
  }
}
