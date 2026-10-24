module.exports = {
    testEnvironment: 'jsdom',

    transform: {
        '^.+\\.[jt]sx?$': 'babel-jest',
    },

    moduleNameMapper: {
        '\\.(css|less|scss)$': '<rootDir>/tests/mocks/styleMock.cjs',
    },

    setupFilesAfterEnv: ['<rootDir>/tests/setupTests.js'],

    testMatch: ['<rootDir>/tests/js/**/*.test.jsx'],
};