# A sample Guardfile
# More info at https://github.com/guard/guard#readme

guard 'phpunit2', :cli => '--colors', tests_path: 'tests' do

  watch(%r{^.+tests\.php$})

  watch(%r{lib/RubyRainbows/Cache/(.+)\.php}) { |m| "tests/RubyRainbows/Cache/#{m[1]}Test.php" }
end